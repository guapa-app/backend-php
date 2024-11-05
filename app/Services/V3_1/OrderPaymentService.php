<?php

namespace App\Services\V3_1;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Enums\AppointmentOfferEnum;
use App\Enums\OrderTypeEnum;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\OrderNotification;
use App\Services\CouponService;
use App\Services\LoyaltyPointsService;
use App\Services\PaymentService;
use App\Services\PDFService;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class OrderPaymentService
{
    protected $loyaltyPointsService;
    protected $walletService;
    public function __construct(
        LoyaltyPointsService $loyaltyPointsService,
        WalletService $walletService
    ) {
        $this->loyaltyPointsService = $loyaltyPointsService;
        $this->walletService = $walletService;
    }
    public function changeOrderStatus(array $data): void
    {
        $order = Order::findOrFail($data['id']);

        if ($data['status'] == 'paid') {
            $order->status = 'Accepted';
            $order->payment_id = $data['payment_id'];
            $order->payment_gateway = $data['payment_gateway'];

            if (!str_contains($order->invoice_url, '.s3.')) {
                $order->invoice_url = (new PDFService)->addInvoicePDF($order);
            }
            $order->save();
            // Update invoice status
            $order->invoice->update(['status' => 'paid']);

            if ($order->type == OrderTypeEnum::Appointment->value) {
                $order->appointmentOfferDetails->update([
                    'status' => AppointmentOfferEnum::Paid_Appointment_Fees->value,
                ]);
                $order->appointmentOfferDetails->appointmentOffer->update([
                    'status' => AppointmentOfferEnum::Paid_Appointment_Fees->value,
                ]);
            }

            // Send email notifications
            $this->sendOrderNotifications($order);
            Log::info("Order notifications sent");

            $this->loyaltyPointsService->addPurchasePoints($order);
            Log::info("Loyalty points added");
        }else {
            $order->status = $data['status'];
            $order->save();
        }
    }
    protected function sendOrderNotifications(Order $order)
    {
        // Send email to admin
        $adminEmails = Admin::role('admin')->pluck('email')->toArray();
        Notification::route('mail', $adminEmails)
            ->notify(new OrderNotification($order));

        // Send email to vendor staff
        Notification::send($order->vendor->staff, new OrderNotification($order));

        // Send email to customer
        $order->user->notify(new OrderNotification($order));
    }
    /**
     * Pay Via Wallet
     *
     * @param  mixed $data
     * @return void
     */
    public function payViaWallet(User $user, array $data): void
    {
        $order = Order::findOrFail($data['id']);
        if ($order->status->value != 'Accepted') {
            $wallet = $user->myWallet();
            $orderPrice = $order->paid_amount_with_taxes;
            if ($wallet->balance >= $orderPrice) {
                try {
                    DB::beginTransaction();
                    $transaction = $this->walletService->debit($user, $orderPrice);
                    $data['payment_id'] = $transaction->transaction_number;
                    $this->changeOrderStatus($data);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Transaction failed: ' . $e->getMessage());
                    throw $e;
                }
            }else{
                throw ValidationException::withMessages([
                    'message' => __('There is no sufficient balance'),
                ]);
            }
        }
    }

}
