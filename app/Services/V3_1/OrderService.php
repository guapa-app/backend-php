<?php

namespace App\Services\V3_1;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Enums\OrderStatus;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\UserVendor;
use App\Notifications\AddVendorClientNotification;
use App\Notifications\OrderNotification;
use App\Notifications\OrderUpdatedNotification;
use App\Services\CouponService;
use App\Services\PaymentService;
use App\Services\PDFService;
use App\Services\QrCodeService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class OrderService
{
    protected $repository;
    protected $paymentService;
    protected $productFees;
    protected $taxesPercentage;
    protected $couponService;
    protected $qrCodeData;

    public function __construct(
        OrderRepositoryInterface $repository,
        PaymentService $payment_service,
        CouponService $coupon_service
    ) {
        $this->repository = $repository;
        $this->paymentService = $payment_service;
        $this->couponService = $coupon_service;
        $this->productFees = Setting::getProductFees();
        $this->taxesPercentage = Setting::getTaxes();
        $this->fees = 0;
        $this->taxes = 0;
    }

    public function create(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $productIds = array_column($data['products'], 'id');
            $products = Product::whereIn('id', $productIds)->get();

            // Apply coupon if provided
            $couponResult = null;
            if (isset($data['coupon_code'])) {
                $couponResult = $this->couponService->applyCoupon($data['coupon_code'], $data, true);
                if (!$couponResult['status']) {
                    abort(400, $couponResult['error']);
                }
            }

            $productsTitles = $products->pluck('title')->implode(' - ');

            // Group products by vendor to create an order for each vendor
            $keyedProducts = $products->groupBy('vendor_id');

            $orders = new Collection;
            $now = now();

            foreach ($keyedProducts as $vendorId => $vendorProducts) {
                $data['vendor_id'] = $vendorId;
                $data['coupon_id'] = $couponResult ? $couponResult['data']['coupon_id'] : null;

                $orderData = $this->calculateOrderData($vendorProducts, $data, $couponResult);

                $order = $this->repository->create($orderData);

                $items = $this->createOrderItems($vendorProducts, $data, $order, $now, $vendorId, $couponResult);

                // Check that provided user ids belong to vendor
                $userIds = $items->pluck('user_id')->filter()->all();

                if (!$this->checkVendorUsers($vendorId, $userIds)) {
                    abort(400, 'Invalid staff provided for vendor');
                }

                $items->map(function ($item) use ($order) {
                    $orderItem = OrderItem::create($item);

                    $qrCodeImage = (new QrCodeService())->generate($this->qrCodeData[$item['product_id']]);

                    $orderItem->addMediaFromString($qrCodeImage)->toMediaCollection('order_items');
                });

                $order->load('items', 'address', 'user', 'vendor');

                $this->taxes = ($this->taxesPercentage / 100) * $orderData['fees'];
                // Generate invoice for the order
                $invoice = $this->paymentService->generateInvoice($order, $productsTitles, $orderData['fees'], $this->taxes);
                $order->invoice_url = $invoice->url;
                $order->save();

                $orders->push($order);

                // Update coupon usage if a coupon was applied
                if ($couponResult) {
                    $this->updateCouponUsage($couponResult['data']['coupon_id'], $data['user_id']);
                }
            }

            return $orders;
        });
    }

    public function checkVendorUsers(int $vendorId, array $userIds): bool
    {
        return UserVendor::where('vendor_id', $vendorId)
                ->whereIn('user_id', $userIds)->count() === count(array_unique($userIds));
    }

    public function update(int $id, array $data): Order
    {
        $order = $this->repository->getOneOrFail($id);

        $this->validateStatus($order, $data['status']);

        $order = $this->repository->update($order, $data);

        $user = $order->user;
        $vendor = $order->vendor;
        // add the client to vendor client list if the order is used
        if ($data['status'] == (OrderStatus::Used)->value) {
            $vendor->clients()->firstOrCreate(['user_id' => $user->id]);
            Notification::send($user, new AddVendorClientNotification($vendor, false));
        }
        if ($data['status'] == (OrderStatus::Canceled)->value && ($order->invoice != null)) {
            $this->paymentService->refund($order);
        }

        Notification::send($user, new OrderUpdatedNotification($order));

        return $order;
    }

    public function getOrderDetails(int $id): Order
    {
        $user = app('cosmo')->user();
        $order = Order::withSingleRelations()->where('id', $id)->firstOrFail();
        if ($order->user_id != $user->id && !$user->hasVendor($order->vendor_id)) {
            abort(403, __('api.order_not_available_for', ['status' => __('view_details')]));
        }

        return $order;
    }

    public function validateStatus(Order $order, string $req_status): void
    {
        $user = app('cosmo')->user();

        if ($user->isAdmin()) {
            return;
        }

        if (in_array($req_status, OrderStatus::availableForUpdateByVendor())) {
            $authorizedVendorIds = Product::query()->select('vendor_id')->whereIn('id', function ($q) use ($order) {
                $q->select('product_id')->from('order_items')->where('order_id', $order->id);
            })->pluck('vendor_id')->toArray();

            if (!$user->hasAnyVendor(array_merge($authorizedVendorIds, [$order->vendor_id]))) {
                $error = __('api.order_authorization_error');
            }
        } elseif ($req_status == (OrderStatus::Cancel_Request)->value) {
            $error = $this->checkUserAuthorization($order, $user);

            if ($order->created_at->addDays(14)->toDateString() < Carbon::today()->toDateString()) {
                $error = __('api.cancel_order_error');
            } elseif (in_array($order->status->value, OrderStatus::notAvailableForCancle())) {
                $error = __(
                    'api.not_available_for_action',
                    ['status' => __('api.order_statuses.' . $order->status->value)]
                );
            }
        } elseif ($req_status == (OrderStatus::Return_Request)->value) {
            $error = $this->checkUserAuthorization($order, $user);

            if ($order->status != OrderStatus::Delivered) {
                $error = __(
                    'api.not_available_for_action',
                    ['status' => __('api.order_statuses.' . $order->status->value)]
                );
            }
        }

        if ($order->status->value === $req_status) {
            $error = __(
                'api.order_status_req_status_error',
                ['status' => __('api.order_statuses.' . $order->status->value)]
            );
        }

        if (isset($error)) {
            throw ValidationException::withMessages([
                'status' => $error,
            ]);
        }
    }

    //changeOrderStatus
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

            // Send email notifications
            $this->sendOrderNotifications($order);
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
    private function checkUserAuthorization($order, $user): ?string
    {
        return ($order->user_id !== $user->id)
            ? __('api.order_authorization_error')
            : null;
    }

    private function calculateOrderData($vendorProducts, $data, $couponResult) : array
    {
        $orderData = $data;
        $orderData['total'] = 0;
        $orderData['fees'] = 0;
        $orderData['discount_amount'] = 0;

        $couponProductDiscounts = $couponResult ? $couponResult['data']['product_discounts'] : [];

        foreach ($vendorProducts as $product) {
            $inputItem = Arr::first($data['products'], fn ($value) => (int) $value['id'] === $product->id);
            $quantity = $inputItem['quantity'];

            if (isset($couponProductDiscounts[$product->id])) {
                // Use discounted values for coupon-eligible products
                $discountedProduct = $couponProductDiscounts[$product->id];
                $orderData['total'] += $discountedProduct['total'];
                $orderData['fees'] += $discountedProduct['fees'];
                $orderData['discount_amount'] += $discountedProduct['discount_amount'];
            } else {
                // Use regular pricing for non-eligible products
                $price = $this->getDiscountedPrice($product);
                $finalPrice = $price * $quantity;
                $productFees = $this->calculateProductFees($product, $finalPrice);

                $orderData['total'] += $finalPrice;
                $orderData['fees'] += $productFees;
            }
        }

        return $orderData;
    }

    private function createOrderItems($vendorProducts, $data, $order, $now, $vendorId, $couponResult): Collection
    {
        return $vendorProducts->map(function ($product) use ($data, $order, $now, $vendorId, $couponResult) {
            $inputItem = Arr::first($data['products'], fn ($value) => (int) $value['id'] === $product->id);

            if (isset($inputItem['appointment'])) {
                $appointment = Appointment::find($inputItem['appointment']['id']);

                if ($appointment->vendor_id !== $vendorId) {
                    $order->delete();
                    abort(400, 'Invalid appointment selected');
                }

                $inputItem['appointment']['from_time'] = $appointment->from_time;
                $inputItem['appointment']['to_time'] = $appointment->to_time;
            }

            $itemAmountToPay = $this->productAmountToPay($product, $inputItem['quantity'], $couponResult);
            $itemPriceAfterDiscount = $this->getDiscountedPrice($product);

            $this->qrCodeData[$product->id] = [
                'hash_id'                   => $product->hash_id,
                'order_id'                  => $order->id,
                'client_name'               => auth()->user()?->name,
                'client_phone'              => auth()->user()?->phone,
                'vendor_name'               => $product->vendor?->name,
                'paid_amount'               => $itemAmountToPay,
                'remain_amount'             => ($product->price - $itemAmountToPay),
                'title'                     => $product->title,
                'item_price'                => $product->price,
                'item_price_after_discount' => $itemPriceAfterDiscount ?? null,
                'item_image'                => $product->image?->url,
            ];

            return [
                'order_id'      => $order->id,
                'product_id'    => $product->id,
                'offer_id'      => optional($product->offer)->id,
                'amount'        => $itemPriceAfterDiscount,
                'amount_to_pay' => $itemAmountToPay,
                'taxes'         => $this->taxesPercentage,
                'quantity'      => $inputItem['quantity'],
                'appointment'   => isset($inputItem['appointment']) ? json_encode($inputItem['appointment']) : null,
                'user_id'       => $inputItem['staff_user_id'] ?? null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        });
    }

    private function getDiscountedPrice($product) : float|int
    {
        $price = $product->price;
        if ($product->offer) {
            $price -= ($price * ($product->offer->discount / 100));
            $price = round($price, 2);
        }

        return $price;
    }

    private function calculateProductFees($product, $finalPrice): float|int
    {
        $productCategory = $product->taxonomies()->first();

        if ($productCategory?->fees) {
            $productFees = $productCategory->fees;

            return ($productFees / 100) * $finalPrice;
        } else {
            return $productCategory?->fixed_price ?? 0;
        }
    }

    private function productAmountToPay($product, $quantity, $couponResult) : float|int
    {
        if ($couponResult && isset($couponResult['data']['product_discounts'][$product->id])) {
            $discountedProduct = $couponResult['data']['product_discounts'][$product->id];
            $productFees = $discountedProduct['fees'];
        } else {
            $price = $this->getDiscountedPrice($product);
            $finalPrice = $price * $quantity;
            $productFees = $this->calculateProductFees($product, $finalPrice);
        }

        return $productFees;
    }

    private function updateCouponUsage($couponId, $userId) : void
    {
        $coupon = Coupon::find($couponId);
        $coupon->usages()->updateOrCreate(
            ['user_id' => $userId],
            ['usage_count' => DB::raw('usage_count + 1')]
        );
    }
}
