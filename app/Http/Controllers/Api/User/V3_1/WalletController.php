<?php

namespace App\Http\Controllers\Api\User\V3_1;

use Illuminate\Http\Request;
use App\Enums\TransactionType;
use App\Services\WalletService;
use App\Services\PaymentService;
use App\Enums\LoyaltyPointAction;
use App\Services\TransactionService;
use App\Models\WalletChargingPackage;
use App\Http\Resources\WalletResource;
use App\Services\LoyaltyPointsService;
use App\Http\Resources\TransactionResource;
use App\Http\Controllers\Api\BaseApiController;

class WalletController extends BaseApiController
{
    protected $walletService;
    protected $transactionService;
    protected $loyaltyPointsService;
    protected $paymentService;

    public function __construct(WalletService $walletService, TransactionService $transactionService, LoyaltyPointsService $loyaltyPointsService, PaymentService $paymentService)
    {
        $this->walletService = $walletService;
        $this->transactionService = $transactionService;
        $this->loyaltyPointsService = $loyaltyPointsService;
        $this->paymentService = $paymentService;
    }

    public function show(Request $request)
    {
        $wallet = $request->user()->myWallet();
        return new WalletResource($wallet);
    }

    public function charge(Request $request)
    {
        $request->validate([
            'payment_id' => 'required',
            'package_id' => 'required|exists:wallet_charging_packages,id',
        ]);

        $payment_id = $request->payment_id;
        if ($this->paymentService->isPaymentPaidSuccessfully($payment_id)) {
            $package = WalletChargingPackage::findOrFail($request->package_id);
            $wallet = $request->user()->myWallet();

            $transactionType = TransactionType::RECHARGE;
            $amount = $package->amount;
            $userId = $request->user()->id;

            // Call the service to create the transaction
            $transaction = $this->transactionService->createTransaction($userId, $amount, $transactionType);

            $wallet->balance += $package->amount;
            $wallet->save();

            $this->loyaltyPointsService->addPoints($userId, $package->points, LoyaltyPointAction::WALLET_CHARGING->value);

            return new TransactionResource($transaction);
        } else {
            return $this->errorJsonRes([], __('Payment details are incorrect. Please check your payment again.'));
        }
    }
}
