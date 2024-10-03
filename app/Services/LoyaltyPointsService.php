<?php

namespace App\Services;

use App\Enums\LoyaltyPointAction;
use App\Models\Setting;
use App\Models\Transaction;
use App\Enums\TransactionType;
use App\Models\LoyaltyPointHistory;
use App\Http\Resources\TransactionResource;

class LoyaltyPointsService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Add points for a specific action.
     *
     * @param int $userId
     * @param int $points
     * @param string $action
     */
    public function addPoints(int $userId, int $points, string $action)
    {
        LoyaltyPointHistory::create([
            'user_id' => $userId,
            'points' => abs($points), // Store points as positive
            'action' => $action,
            'type' => 'added',
        ]);
    }

    /**
     * Subtract points for a specific action.
     *
     * @param int $userId
     * @param int $points
     * @param string $action
     */
    public function subtractPoints(int $userId, int $points, string $action)
    {
        LoyaltyPointHistory::create([
            'user_id' => $userId,
            'points' => -abs($points), // Store points as negative
            'action' => $action,
            'type' => 'subtracted',
        ]);
    }

    /**
     * Convert points to balance and record the transaction.
     *
     * @param int $userId
     * @param int $points
     * @return Transaction
     */
    public function convertPointsToBalance(int $userId, int $points)
    {
        $conversionRate = Setting::pointsConversionRate();

        $wallet = auth()->user()->myWallet();

        if ($points > $wallet->points) {
            return response()->json(['message' => __('Not enough points to convert')], 400);
        }

        $pointsToConvert = min($points, $wallet->points);
        $cashAmount = $pointsToConvert / $conversionRate;

        if ($pointsToConvert > 0) {

            // Check if the amount is a multiple of the conversion rate
            if (!$this->canConvertPoints($points)) {
                return response()->json(['message' => __('The points count must be a multiple of the conversion rate (:paypal).', ['paypal' => $conversionRate])], 400);
            }

            $transactionType = TransactionType::POINTS_TRANSFER;
            $amount = $cashAmount;

            // Call the service to create the transaction
            $transaction = $this->transactionService->createTransaction($userId, $amount, $transactionType);

            $wallet->points -= $pointsToConvert;
            $wallet->balance += $cashAmount;

            $wallet->save();

            $this->subtractPoints($userId, $points, LoyaltyPointAction::CONVERSION->value);

            return new TransactionResource($transaction);
        }

        return response()->json(['message' => __('Not enough points to convert')], 400);
    }

    /**
     * Get total points for a user.
     *
     * @param int $userId
     * @return int
     */
    public function getTotalPoints(int $userId)
    {
        // Sum points: positive values for addition and negative for subtraction
        return LoyaltyPointHistory::where('user_id', $userId)->sum('points');
    }

    /**
     * Get points history for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPointsHistory(int $userId)
    {
        return LoyaltyPointHistory::where('user_id', $userId)->get();
    }

    /**
     * Check if the points count can be converted.
     *
     * @param int $points
     * @return bool
     */
    public function canConvertPoints(int $points)
    {
        $conversionRate = Setting::pointsConversionRate();

        return $points % $conversionRate === 0;
    }
}
