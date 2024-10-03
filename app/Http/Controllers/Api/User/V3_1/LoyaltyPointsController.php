<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\LoyaltyPointsService;
use App\Http\Controllers\Api\BaseApiController;

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

        return response()->json(['total_points' => $totalPoints]);
    }

    public function pointsHistory(Request $request)
    {
        $userId = $request->user()->id;
        $history = $this->loyaltyPointsService->getPointsHistory($userId);

        return response()->json($history);
    }

    public function calcConvertPointsToCash(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
        ]);

        $conversionRate = Setting::pointsConversionRate();

        $wallet = $request->user()->myWallet();

        if ($request->points > $wallet->points) {
            return response()->json(['message' => __('Not enough points to convert')], 400);
        }

        $pointsToConvert = min($request->points, $wallet->points);
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
