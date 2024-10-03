<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\Setting;
use App\Enums\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Models\WalletChargingPackage;
use Illuminate\Http\Request;

class WalletService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Charge the user's wallet with a specific amount of points.
     *
     * @param int $userId
     * @param int $amount
     * @return Wallet
     */
    public function chargeWallet(Request $request, int $userId, int $amount)
    {
        $package = WalletChargingPackage::findOrFail($request->package_id);
        $wallet = $request->user()->myWallet();

        $transactionType = TransactionType::RECHARGE;
        $amount = $package->amount;
        $userId = $request->user()->id;

        // Call the service to create the transaction
        $this->transactionService->createTransaction($userId, $amount, $transactionType);


        $wallet->balance += $package->amount;
        $wallet->points += $package->points;
        $wallet->save();

        return $wallet;
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
     * Get the user's wallet balance.
     *
     * @param int $userId
     * @return float
     */
    public function getBalance(int $userId)
    {
        $wallet = Wallet::where('user_id', $userId)->first();

        return $wallet ? $wallet->balance : 0;
    }

    /**
     * Get the user's wallet transactions.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionHistory(int $userId)
    {
        return Wallet::find($userId)->transactions()->get();
    }
}
