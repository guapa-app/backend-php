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
use App\Services\NotificationMigrationHelper;
use Illuminate\Validation\ValidationException;

class OrderPaymentService
{
    protected $notificationHelper;

    public function __construct(NotificationMigrationHelper $notificationHelper)
    {
        $this->notificationHelper = $notificationHelper;
    }

    public function changeOrderStatus(array $data): void
    {
        try {
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
                    //                    $walletService->creditVendorWallet($order->vendor_id, $amount, $order->id);
                    $walletService->creditVendorWallet($order->vendor_id, $amount, $order);
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

            // Send invoice notifications via unified service
            $this->notificationHelper->sendInvoiceNotification($order, $order->user, 'user');
            $this->notificationHelper->sendInvoiceNotification($order, $order->vendor, 'vendor');
            $this->notificationHelper->sendInvoiceNotification($order, 'admin', 'admin');
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

            // Send notification to customer via unified service
            $this->notificationHelper->sendOrderNotification($order, $order->user, false); // false = customer notification

            // Send notification to vendor staff via unified service
            if ($order->vendor && $order->vendor->staff) {
                $this->notificationHelper->sendToMultiple(
                    module: 'new-order',
                    title: 'New Order Received',
                    summary: "New order #{$order->id} from {$order->user->name}",
                    data: [
                        'order_id' => $order->id,
                        'customer_name' => $order->user->name,
                        'total_amount' => $order->total,
                        'type' => $order->type
                    ],
                    notifiables: $order->vendor->staff
                );
            }

            // Send notification to vendor via unified service
            $this->notificationHelper->sendOrderNotification($order, $order->vendor, true); // true = vendor notification

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
