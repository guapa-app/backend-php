<?php

namespace App\Jobs;

use App\Enums\TransactionOperation;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Admin;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Notifications\PayoutStatusNotification;
use App\Services\PaymentService;
use App\Services\WalletService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessVendorPayouts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var array
     */
    public $backoff = [60, 180, 360];


    /**
     * Execute the job.
     */
    public function handle(PaymentService $paymentService, WalletService $walletService)
    {
        // Get all vendors with available balance
        $vendorWallets = Wallet::where('vendor_id', '!=', null)
            ->where('balance', '>', 0)
            ->with('vendor')
            ->get();

        foreach ($vendorWallets as $wallet) {
            try {
                DB::beginTransaction();


                $transaction = Transaction::create([
                    'vendor_id' => $wallet->vendor_id,
                    'transaction_number' => 'PAYOUT-' . uniqid(),
                    'amount' => -$wallet->balance, // Negative amount for withdrawal
                    'operation' => TransactionOperation::WITHDRAWAL,
                    'transaction_type' => TransactionType::VENDOR_PAYOUT,
                    'status' => TransactionStatus::PROCESSING,
                    'transaction_date' => now()
                ]);

                // TODO:Process transfer through payment gateway
                $result = $paymentService->transferToVendor(
                    $wallet->balance,
                    $wallet->vendor->iban
                );

                if ($result['success']) {
                    // Update wallet balance
                    $wallet->balance = 0;
                    $wallet->save();

                    // Update transaction record
                    $transaction->update([
                        'status' => TransactionStatus::COMPLETED,
                        'transaction_number' => $result['transaction_id']
                    ]);

                    // Send notification to vendor
                    $wallet->vendor->notify(new PayoutStatusNotification($transaction));

                    DB::commit();
                } else {
                    throw new \Exception($result['error'] ?? 'Payment gateway transfer failed');
                }
            } catch (\Exception $e) {
                DB::rollBack();

                // Update transaction to failed status
                $transaction->update([
                    'status' => TransactionStatus::FAILED,
                    'notes' => $e->getMessage()
                ]);

                Log::error('Vendor payout failed', [
                    'vendor_id' => $wallet->vendor_id,
                    'amount' => $wallet->balance,
                    'error' => $e->getMessage()
                ]);

                // Notify admins about the failure
                $adminEmails = Admin::role('admin')->pluck('email')->toArray();
                Notification::route('mail', $adminEmails)
                    ->notify(new PayoutStatusNotification($transaction));
            }
        }
    }
}
