<?php

namespace App\Services\V3_1;

use App\Models\User;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderNotify;
use App\Enums\OrderTypeEnum;
use App\Services\PDFService;
use App\Services\WalletService;
use Illuminate\Support\Facades\DB;
use App\Enums\AppointmentOfferEnum;
use Illuminate\Support\Facades\Log;
use App\Services\LoyaltyPointsService;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class OrderPaymentService
{
    public function changeOrderStatus(array $data): void
    {
        try
        {
            $order = Order::findOrFail($data['id']);

            if ($data['status'] == 'paid') {
                $this->processPaidOrder($order, $data);
            } else {
                $order->update(['status' => $data['status']]);
            }

            if ($data['status'] == 'paid') {

                $loyaltyPointsService = app(LoyaltyPointsService::class);
                $loyaltyPointsService->addPurchasePoints($order);

                if ($order->vendor_wallet) {
                    $walletService = app(WalletService::class);
                    $amount = $order->total - $order->fees;
                    $walletService->creditVendorWallet($order->vendor_id, $amount, $order->id);
                }

                $this->sendOrderNotifications($order);
            }
        } catch (\Exception $e) {
            Log::error('Order status change failed: ' . $e->getMessage(), [
                'order_id' => $data['id'],
                'status' => $data['status']
            ]);
            throw $e;
        }
    }

    protected function processPaidOrder(Order $order, array $data): void
    {
        // Update order details
        $order->fill([
            'status' => 'Accepted',
            'payment_id' => $data['payment_id'],
            'payment_gateway' => $data['payment_gateway']
        ]);

        // Generate invoice if needed
        if (!str_contains($order->invoice_url, '.s3.')) {
            $order->invoice_url = (new PDFService)->addInvoicePDF($order);
        }
        $order->save();


        // Update related records
        $order->invoice->update(['status' => 'paid']);

        if ($order->type == OrderTypeEnum::Appointment->value) {
            $this->updateAppointmentStatus($order);
        }
    }

    protected function updateAppointmentStatus(Order $order): void
    {
        $status = AppointmentOfferEnum::Paid_Appointment_Fees->value;

        DB::table('appointment_offer_details')
            ->where('order_id', $order->id)
            ->update(['status' => $status]);

        DB::table('appointment_offers')
            ->where('id', $order->appointmentOfferDetails->appointment_offer_id)
            ->update(['status' => $status]);
    }

    protected function sendOrderNotifications(Order $order)
    {
        try {
            // Load order notify to handle notification ShouldQueue infinty loop
            $order = OrderNotify::findOrFail($order->id);

            // Send email to admin
//            $adminEmails = Admin::role('admin')->pluck('email')->toArray();
//            Notification::route('mail', $adminEmails)
//                ->notify(new OrderNotification($order));

            // Send email to vendor staff
//            Notification::send($order->vendor, new OrderNotification($order));

            // Send email to customer
            $order->user->notify(new OrderNotification($order));
        } catch (\Exception $e) {
            Log::error('Failed to send order notifications: ' . $e->getMessage(), [
                'order_id' => $order->id
            ]);
        }
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
                    $walletService = app(WalletService::class);
                    $transaction = $walletService->debit($user, $orderPrice);
                    $data['payment_id'] = $transaction->transaction_number;
                    $this->changeOrderStatus($data);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Transaction failed: ' . $e->getMessage());
                    throw $e;
                }
            } else {
                throw ValidationException::withMessages([
                    'message' => __('There is no sufficient balance'),
                ]);
            }
        }
    }
}
