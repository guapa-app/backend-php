<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enums\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionOperation;
use App\Models\WalletChargingPackage;

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
     * Credit amount to vendor wallet and create a transaction
     * 
     * @param int $vendorId Vendor ID to credit
     * @param float $amount Amount to credit
     * @param mixed $source The source model (Order or Transaction)
     * @param string|null $sourceType Optional override for source type
     * @return Transaction
     */
    public function creditVendorWallet(int $vendorId, float $amount, $source, ?string $sourceType = null): Transaction
    {
        // get first wallet for vendor or create
        $wallet = Wallet::firstOrCreate(['vendor_id' => $vendorId]);

        // Determine source type and ID
        $sourceableType = $sourceType ?? get_class($source);
        $sourceableId = $source->id;

        // Create pending transaction
        $transaction = $this->transactionService->createVendorTransaction([
            'vendor_id' => $vendorId,
            'amount' => $amount,
            'transaction_type' => TransactionType::ORDER_PAYMENT,
            'operation' => TransactionOperation::DEPOSIT,
            'sourceable_type' => $sourceableType,
            'sourceable_id' => $sourceableId,
            'status' => TransactionStatus::PENDING,
        ]);

        $wallet->balance += $amount;
        $wallet->save();

        return $transaction;
    }

    /**
     * debit the user's wallet with a specific amount.
     *
     * @param User $user
     * @param float $amount
     * @return Transaction
     */
    public function debit(User $user, float $amount)
    {
        $wallet = $user->myWallet();

        $transactionType = TransactionType::DEBIT_FROM_WALLET;
        $transactionOperation = TransactionOperation::WITHDRAWAL;
        // Call the service to create the transaction
        $transaction = $this->transactionService->createTransaction($user->id, -$amount, $transactionType, $transactionOperation);

        $wallet->balance -= $amount;
        $wallet->save();

        return $transaction;
    }

    /**
     * refund transaction by id.
     * @param int $transactionId
     * @return bool
     */
    public function refund(int $transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return false;
        }

        $wallet = Wallet::where('user_id', $transaction->user_id)->first();

        if (!$wallet) {
            return false;
        }

        $transactionType = TransactionType::RECHARGE;
        // Call the service to create the transaction
        $this->transactionService->createTransaction($transaction->user_id, $transaction->amount, $transactionType);

        $wallet->balance += $transaction->amount;
        $wallet->save();

        return true;
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
