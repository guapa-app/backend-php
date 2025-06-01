<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Transaction;
use App\Enums\TransactionType;
use App\Models\WheelOfFortune;
use App\Enums\LoyaltyPointAction;
use App\Models\LoyaltyPointHistory;
use App\Models\WalletChargingPackage;
use App\Models\ExchangeReward;
use App\Models\ExchangeTransaction;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\LoyaltyPointHistoryResource;

class LoyaltyPointsService
{
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
    public function subtractPoints($sourceable, int $userId, int $points, string $action)
    {
        $sourceable->loyaltyPointHistories()->create([
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

        $totalPoints = $this->getTotalPoints($userId);

        if ($points > $totalPoints) {
            return response()->json(['message' => __('Not enough points to convert')], 400);
        }

        $pointsToConvert = min($points, $totalPoints);
        $cashAmount = $pointsToConvert / $conversionRate;

        if ($pointsToConvert > 0) {

            // Check if the amount is a multiple of the conversion rate
            if (!$this->canConvertPoints($points)) {
                return response()->json(['message' => __('The points count must be a multiple of the conversion rate (:paypal).', ['paypal' => $conversionRate])], 400);
            }

            $transactionType = TransactionType::POINTS_TRANSFER;
            $amount = $cashAmount;

            // Call the service to create the transaction
            $transactionService = app(TransactionService::class);

            $transaction = $transactionService->createTransaction($userId, $amount, $transactionType);

            $user = User::where('id', $userId)->first();
            $wallet = $user->myWallet();
            $wallet->balance += $amount;
            $wallet->save();

            $this->subtractPoints($user->myPointsWallet(), $userId, $points, LoyaltyPointAction::CONVERSION->value);

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
        $histories =  LoyaltyPointHistory::with(['sourceable'])->where('user_id', $userId)->orderBy('id', 'DESC')->get();

        // Iterate through each history to customize the sourceable data
        $histories->each(function ($history) {
            if ($history->sourceable_type == 'product') {
                $history->setRelation('sourceable', $history->sourceable()->select('id', 'title')->first());
            } elseif ($history->sourceable_type == 'user') {
                $history->setRelation('sourceable', $history->sourceable()->select('id', 'name')->first());
            } elseif ($history->sourceable_type === \App\Models\WheelOfFortune::class) {
                $history->setRelation('sourceable', $history->sourceable()->select('id', 'rarity_title')->first());
            } elseif ($history->sourceable_type === \App\Models\WalletChargingPackage::class) {
                $history->setRelation('sourceable', $history->sourceable()->select('id', 'name')->first());
            }
        });

        return LoyaltyPointHistoryResource::collection($histories);
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

    /**
     * Add Wallet Charging Points
     *
     * @param  mixed $order
     * @return void
     */
    public function addWalletChargingPoints($userId, WalletChargingPackage $package)
    {
        $package->loyaltyPointHistories()->create([
            'user_id' => $userId,
            'points' => $package->points,
            'action' => LoyaltyPointAction::WALLET_CHARGING->value,
            'type' => 'added',
        ]);
    }

    /**
     * Add Spin Wheel Points
     *
     * @param  mixed $order
     * @return void
     */
    public function addSpinWheelPoints($userId, WheelOfFortune $wheel)
    {
        $wheel->loyaltyPointHistories()->create([
            'user_id' => $userId,
            'points' => $wheel->points,
            'action' => LoyaltyPointAction::SPIN_WHEEL->value,
            'type' => 'added',
        ]);
    }

    /**
     * Add Purchase Points
     *
     * @param  mixed $order
     * @return void
     */
    public function addPurchasePoints(Order $order)
    {
        $conversionRate = Setting::purchasePointsConversionRate();
        $paidAmount = $order->paid_amount_with_taxes;
        $points = (int) ($paidAmount * $conversionRate);

        $items = $order->items;
        foreach ($items as $item) {
            $product = $item->product;
            if ($product->earned_points > 0) {
                $product->loyaltyPointHistories()->create([
                    'user_id' => $order->user_id,
                    'points' => $product->earned_points,
                    'action' => LoyaltyPointAction::PURCHASE->value,
                    'type' => 'added',
                ]);
            } else {
                $product->loyaltyPointHistories()->create([
                    'user_id' => $order->user_id,
                    'points' => $points,
                    'action' => LoyaltyPointAction::PURCHASE->value,
                    'type' => 'added',
                ]);
            }
        }
    }

    /**
     * Return Purchase Points
     *
     * @param  mixed $order
     * @return void
     */
    public function returnPurchasePoints(Order $order)
    {
        $items = $order->items;
        foreach ($items as $item) {
            $product = $item->product;
            $loyaltyHistories = $product->loyaltyPointHistories()->where('type', 'added')
                ->orderBy('id', 'desc')->get();
            foreach ($loyaltyHistories as $history) {
                $product->loyaltyPointHistories()->create([
                    'user_id' => $order->user_id,
                    'points' => -abs($history->points),
                    'action' => LoyaltyPointAction::RETURN_PURCHASE->value,
                    'type' => 'subtracted',
                ]);
            }
        }
    }


    /**
     * Add Friend Registrations Points
     *
     * @param  User $inviter
     * @param  User $invitee
     * @return void
     */
    public function addFriendRegistrationsPoints(User $inviter, User $invitee)
    {
        $inviterPoints = Setting::inviterEarndPoints();
        $inviteePoints = Setting::inviteeEarndPoints();

        $inviter->loyaltyPointHistories()->create([
            'user_id' => $invitee->id,
            'points' => abs($inviteePoints),
            'action' => LoyaltyPointAction::FRIENDS_REGISTRATIONS->value,
            'type' => 'added',
        ]);

        $inviteePointsWallet = $invitee->myPointsWallet();
        $inviteePointsWallet->points += $inviteePoints;
        $inviteePointsWallet->save();

        $inviterPointsWallet = $inviter->myPointsWallet();
        $inviterPointsWallet->points += $inviterPoints;
        $inviterPointsWallet->save();

        $invitee->loyaltyPointHistories()->create([
            'user_id' => $inviter->id,
            'points' => abs($inviterPoints),
            'action' => LoyaltyPointAction::FRIENDS_REGISTRATIONS->value,
            'type' => 'added',
        ]);
    }

    /**
     * Add Admin User Points
     *
     * @param  mixed $order
     * @return void
     */
    public function addAdminUserPoints(User $user, $points)
    {
        $user->loyaltyPointHistories()->create([
            'user_id' => $user->id,
            'points' => $points,
            'action' => LoyaltyPointAction::SYSTEM_ADDITION->value,
            'type' => 'added',
        ]);
    }

    /**
     * Deduct Admin User Points
     *
     * @param  mixed $order
     * @return void
     */
    public function deductAdminUserPoints(User $user, $points)
    {
        $user->loyaltyPointHistories()->create([
            'user_id' => $user->id,
            'points' => -abs($points),
            'action' => LoyaltyPointAction::SYSTEM_DEDUCTION->value,
            'type' => 'subtracted',
        ]);
    }

    /**
     * Exchange points for rewards (coupons, gift cards, etc.)
     *
     * @param int $userId
     * @param int $rewardId
     * @return array
     */
    public function exchangePointsForReward(int $userId, int $rewardId)
    {
        $reward = ExchangeReward::find($rewardId);

        if (!$reward) {
            return ['success' => false, 'message' => __('Reward not found')];
        }

        if (!$reward->canBeUsedByUser($userId)) {
            return ['success' => false, 'message' => __('Reward is not available or you have reached the usage limit')];
        }

        $userPoints = $this->getTotalPoints($userId);

        if ($userPoints < $reward->points_required) {
            return ['success' => false, 'message' => __('Not enough points to exchange for this reward')];
        }

        // Create exchange transaction
        $exchangeTransaction = ExchangeTransaction::create([
            'user_id' => $userId,
            'exchange_reward_id' => $rewardId,
            'points_used' => $reward->points_required,
            'status' => ExchangeTransaction::STATUS_COMPLETED,
            'exchange_data' => $this->generateExchangeData($reward),
            'expires_at' => $reward->type === ExchangeReward::TYPE_COUPON ? now()->addDays(30) : null,
            'redeemed_at' => now()
        ]);

        // Deduct points from user
        $user = User::find($userId);
        $this->subtractPoints($exchangeTransaction, $userId, $reward->points_required, $this->getActionByRewardType($reward->type));

        // Update reward usage count
        $reward->increment('used_count');

        return [
            'success' => true,
            'message' => __('Points exchanged successfully'),
            'exchange_transaction' => $exchangeTransaction,
            'exchange_data' => $exchangeTransaction->exchange_data
        ];
    }

    /**
     * Get available rewards for exchange
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableRewards(int $userId)
    {
        $userPoints = $this->getTotalPoints($userId);

        return ExchangeReward::available()
            ->where('points_required', '<=', $userPoints)
            ->get()
            ->filter(function ($reward) use ($userId) {
                return $reward->canBeUsedByUser($userId);
            });
    }

    /**
     * Get user's exchange history
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserExchangeHistory(int $userId)
    {
        return ExchangeTransaction::with(['exchangeReward'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Generate exchange data based on reward type
     *
     * @param ExchangeReward $reward
     * @return array
     */
    private function generateExchangeData(ExchangeReward $reward): array
    {
        switch ($reward->type) {
            case ExchangeReward::TYPE_COUPON:
                return [
                    'coupon_code' => 'POINTS' . strtoupper(uniqid()),
                    'discount_value' => $reward->value,
                    'discount_type' => $reward->metadata['discount_type'] ?? 'fixed',
                    'min_order_amount' => $reward->metadata['min_order_amount'] ?? 0,
                ];

            case ExchangeReward::TYPE_GIFT_CARD:
                return [
                    'gift_card_code' => 'GC' . strtoupper(uniqid()),
                    'value' => $reward->value,
                ];

            case ExchangeReward::TYPE_CASH_CREDIT:
                return [
                    'credit_amount' => $reward->value,
                ];

            default:
                return [
                    'reward_value' => $reward->value,
                ];
        }
    }

    /**
     * Get loyalty point action based on reward type
     *
     * @param string $rewardType
     * @return string
     */
    private function getActionByRewardType(string $rewardType): string
    {
        return match ($rewardType) {
            ExchangeReward::TYPE_COUPON => LoyaltyPointAction::COUPON_EXCHANGE->value,
            ExchangeReward::TYPE_GIFT_CARD => LoyaltyPointAction::GIFT_CARD_EXCHANGE->value,
            ExchangeReward::TYPE_SHIPPING_DISCOUNT => LoyaltyPointAction::SHIPPING_DISCOUNT->value,
            ExchangeReward::TYPE_PRODUCT_DISCOUNT => LoyaltyPointAction::PRODUCT_DISCOUNT->value,
            default => LoyaltyPointAction::CONVERSION->value,
        };
    }

    /**
     * Calculate how many points a user would need for a specific reward
     *
     * @param int $userId
     * @param int $rewardId
     * @return array
     */
    public function calculatePointsNeeded(int $userId, int $rewardId)
    {
        $reward = ExchangeReward::find($rewardId);
        $userPoints = $this->getTotalPoints($userId);

        if (!$reward) {
            return ['error' => __('Reward not found')];
        }

        $pointsNeeded = max(0, $reward->points_required - $userPoints);

        return [
            'user_points' => $userPoints,
            'required_points' => $reward->points_required,
            'points_needed' => $pointsNeeded,
            'can_exchange' => $pointsNeeded === 0 && $reward->canBeUsedByUser($userId)
        ];
    }
}
