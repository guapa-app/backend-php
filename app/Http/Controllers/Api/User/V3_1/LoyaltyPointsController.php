<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\LoyaltyPointsService;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\LoyaltyPointHistoryCollection;

class LoyaltyPointsController extends BaseApiController
{
    protected $loyaltyPointsService;

    public function __construct(LoyaltyPointsService $loyaltyPointsService)
    {
        $this->loyaltyPointsService = $loyaltyPointsService;
    }

    public function totalPoints(Request $request)
    {
        $userId = $request->user()->id;

        $totalPoints = $this->loyaltyPointsService->getTotalPoints($userId);

        return response()->json([
            'data' => [
                'points' => (int) $totalPoints,
                'conversion_rate' => Setting::pointsConversionRate(),
                'amount' => $totalPoints / Setting::pointsConversionRate(),
            ],
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    public function pointsHistory(Request $request)
    {
        $userId = $request->user()->id;
        $history = $this->loyaltyPointsService->getPointsHistory($userId);

        return LoyaltyPointHistoryCollection::make($history)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function calcConvertPointsToCash(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
        ]);

        $conversionRate = Setting::pointsConversionRate();

        $totalPoints = $this->loyaltyPointsService->getTotalPoints($request->user()->id);

        if ($request->points > $totalPoints) {
            return response()->json(['message' => __('Not enough points to convert')], 400);
        }

        $pointsToConvert = min($request->points, $totalPoints);
        $cashAmount = $pointsToConvert / $conversionRate;

        if ($pointsToConvert > 0) {
            // Check if the amount is a multiple of the conversion rate
            if (!$this->loyaltyPointsService->canConvertPoints($request->points)) {
                return response()->json(['message' => __('The points count must be a multiple of the conversion rate (:paypal).', ['paypal' => $conversionRate])], 400);
            }

            $amount = $cashAmount;

            return response()->json(['amount' => $amount]);
        }

        return response()->json(['message' => __('Not enough points to convert')], 400);
    }

    public function convertPoints(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
        ]);

        $userId = $request->user()->id;
        $points = $request->input('points');

        return $this->loyaltyPointsService->convertPointsToBalance($userId, $points);
    }

    /**
     * Get available rewards for exchange
     */
    public function getAvailableRewards(Request $request)
    {
        $userId = $request->user()->id;
        $rewards = $this->loyaltyPointsService->getAvailableRewards($userId);

        return response()->json([
            'data' => $rewards,
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    /**
     * Exchange points for a specific reward
     */
    public function exchangePointsForReward(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|integer|exists:exchange_rewards,id',
        ]);

        $userId = $request->user()->id;
        $rewardId = $request->input('reward_id');

        $result = $this->loyaltyPointsService->exchangePointsForReward($userId, $rewardId);

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message'],
                'success' => false
            ], 400);
        }

        return response()->json([
            'data' => [
                'exchange_transaction' => $result['exchange_transaction'],
                'exchange_data' => $result['exchange_data']
            ],
            'success' => true,
            'message' => $result['message'],
        ]);
    }

    /**
     * Get user's exchange history
     */
    public function getExchangeHistory(Request $request)
    {
        $userId = $request->user()->id;
        $history = $this->loyaltyPointsService->getUserExchangeHistory($userId);

        return response()->json([
            'data' => $history,
            'success' => true,
            'message' => __('api.success'),
        ]);
    }

    /**
     * Calculate points needed for a specific reward
     */
    public function calculatePointsNeeded(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|integer|exists:exchange_rewards,id',
        ]);

        $userId = $request->user()->id;
        $rewardId = $request->input('reward_id');

        $calculation = $this->loyaltyPointsService->calculatePointsNeeded($userId, $rewardId);

        if (isset($calculation['error'])) {
            return response()->json([
                'message' => $calculation['error'],
                'success' => false
            ], 400);
        }

        return response()->json([
            'data' => $calculation,
            'success' => true,
            'message' => __('api.success'),
        ]);
    }
}
