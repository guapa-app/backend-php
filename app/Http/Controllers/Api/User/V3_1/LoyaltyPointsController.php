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
}
