<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderNotify;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Admin;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Notifications\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\BaseApiController;

class TestController extends BaseApiController
{
    /**
     * Send test order notification
     * 
     * This endpoint allows testing order notifications by sending notifications
     * for a specific order using the authenticated user's token.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function sendTestOrderNotification(Request $request): JsonResponse
    {
        try {
            // Validate request parameters
            $request->validate([
                'order_id' => 'nullable|integer|exists:orders,id',
                'create_test_order' => 'nullable|boolean',
                'test_user_id' => 'nullable|integer|exists:users,id',
                'test_vendor_id' => 'nullable|integer|exists:vendors,id'
            ]);

            $user = Auth::user();
            
            // If order_id is provided, use existing order
            if ($request->has('order_id')) {
                $order = Order::findOrFail($request->order_id);
                Log::info("Using existing order #{$order->id} for testing notification");
            } 
            // Otherwise create a test order
            else {
                $order = $this->createTestOrder($request, $user);
                Log::info("Created test order #{$order->id} for testing notification");
            }

            // Send notifications using the existing notification system
            $this->sendTestNotifications($order);

            return response()->json([
                'success' => true,
                'message' => 'Test order notification sent successfully',
                'data' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'vendor_id' => $order->vendor_id,
                    'order_status' => $order->status->value,
                    'invoice_url' => $order->invoice_url,
                    'notifications_sent_to' => [
                        'customer' => $order->user->email ?? $order->user->phone,
                        'vendor' => $order->vendor->name ?? 'N/A',
                        'admins' => Admin::role('admin')->pluck('email')->toArray()
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to send test order notification: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test order notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a test order for notification testing
     */
    private function createTestOrder(Request $request, User $authenticatedUser): Order
    {
        // Use specified test user or authenticated user
        $userId = $request->test_user_id ?? $authenticatedUser->id;
        $user = User::findOrFail($userId);

        // Use specified vendor or get first available vendor
        $vendorId = $request->test_vendor_id ?? Vendor::first()?->id;
        if (!$vendorId) {
            throw new \Exception('No vendor available for test order creation');
        }
        $vendor = Vendor::findOrFail($vendorId);

        // Get first country for the order
        $country = Country::first();
        if (!$country) {
            throw new \Exception('No country available for test order creation');
        }

        // Create test order
        $order = Order::create([
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'country_id' => $country->id,
            'total' => 100.00,
            'status' => OrderStatus::Accepted,
            'name' => $user->name,
            'phone' => $user->phone,
            'note' => 'This is a test order created for notification testing',
            'type' => 'regular',
            'payment_gateway' => 'test',
            'payment_id' => 'TEST-PAY-' . time(),
        ]);

        // Create test invoice
        $invoice = Invoice::create([
            'invoiceable_type' => 'App\Models\Order',
            'invoiceable_id' => $order->id,
            'amount' => 115.00, // Including taxes
            'taxes' => 15.00,
            'invoice_id' => 'INV-TEST-' . $order->id,
            'status' => 'paid',
            'currency' => 'SAR',
            'description' => 'Test order invoice for notification testing',
            'callback_url' => url('/api/test/callback'),
            'amount_format' => '115.00',
            'url' => url('/test/invoice'),
            'logo_url' => url('/test/logo'),
        ]);

        // Update order with invoice URL (using order ID instead of hash_id)
        $order->update([
            'invoice_url' => url("/api/user/v3.1/orders/{$order->id}/invoice")
        ]);

        // Create test order item
        OrderItem::create([
            'order_id' => $order->id,
            'title' => 'Test Product for Notification',
            'amount' => 100.00,
            'amount_to_pay' => 100.00,
            'quantity' => 1,
            'taxes' => 15.00,
            'product_id' => 1, // Use first product or create a dummy ID
        ]);

        return $order;
    }

    /**
     * Send test notifications using the existing notification system
     */
    private function sendTestNotifications(Order $order): void
    {
        // Load order notify to handle notification (prevents infinite loops)
        $orderNotify = OrderNotify::findOrFail($order->id);

        Log::info("Sending test notifications for order #{$order->id}");

        // Send notification to customer
        $order->user->notify(new OrderNotification($orderNotify));
        Log::info("Sent notification to customer: {$order->user->email}");

        // Send notification to vendor staff
        if ($order->vendor) {
            Notification::send($order->vendor, new OrderNotification($orderNotify));
            Log::info("Sent notification to vendor: {$order->vendor->name}");
        }

        // Send notification to admin
        $adminEmails = Admin::role('admin')->pluck('email')->toArray();
        if (!empty($adminEmails)) {
            Notification::route('mail', $adminEmails)
                ->notify(new OrderNotification($orderNotify));
            Log::info("Sent notification to admins: " . implode(', ', $adminEmails));
        }
    }

    /**
     * Basic test endpoint without authentication
     */
    public function testBasic(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Test controller v3.1 is working',
            'timestamp' => now(),
            'users_count' => User::count(),
            'vendors_count' => Vendor::count(),
            'api_version' => 'user/v3.1'
        ], 200);
    }

    /**
     * Get test order details for debugging
     */
    public function getTestOrderDetails(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id'
            ]);

            $order = Order::with(['user', 'vendor', 'items', 'invoice'])
                ->findOrFail($request->order_id);

            return response()->json([
                'success' => true,
                'data' => [
                    'order' => [
                        'id' => $order->id,
                        'status' => $order->status->value,
                        'total' => $order->total,
                        'invoice_url' => $order->invoice_url,
                        'user' => [
                            'id' => $order->user->id,
                            'name' => $order->user->name,
                            'email' => $order->user->email,
                            'phone' => $order->user->phone,
                        ],
                        'vendor' => $order->vendor ? [
                            'id' => $order->vendor->id,
                            'name' => $order->vendor->name,
                        ] : null,
                        'items' => $order->items->map(function ($item) {
                            return [
                                'title' => $item->title,
                                'amount_to_pay' => $item->amount_to_pay,
                                'quantity' => $item->quantity,
                                'taxes' => $item->taxes,
                            ];
                        }),
                        'invoice' => $order->invoice ? [
                            'amount' => $order->invoice->amount,
                            'taxes' => $order->invoice->taxes,
                            'invoice_id' => $order->invoice->invoice_id,
                        ] : null,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get test order details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}