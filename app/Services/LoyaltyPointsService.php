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
use App\Http\Resources\TransactionResource;
use App\Http\Resources\LoyaltyPointHistoryResource;

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
            $transaction = $this->transactionService->createTransaction($userId, $amount, $transactionType);

            $user = User::find($userId)->first();

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
            if ($product->earned_points) {
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
}
