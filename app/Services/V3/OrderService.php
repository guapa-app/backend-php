<?php

namespace App\Services\V3;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Appointment;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CouponService;
use App\Services\OrderService as BaseOrderService;
use App\Services\PaymentService;
use App\Services\QrCodeService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService extends BaseOrderService
{
    protected $couponService;
    protected $qrCodeData;

    public function __construct(
        OrderRepositoryInterface $repository,
        PaymentService $payment_service,
        CouponService $coupon_service
    ) {
        parent::__construct($repository, $payment_service);
        $this->couponService = $coupon_service;
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

    private function calculateOrderData($vendorProducts, $data, $couponResult)
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

    private function createOrderItems($vendorProducts, $data, $order, $now, $vendorId, $couponResult)
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

    private function getDiscountedPrice($product)
    {
        $price = $product->price;
        if ($product->offer) {
            $price -= ($price * ($product->offer->discount / 100));
            $price = round($price, 2);
        }

        return $price;
    }

    private function calculateProductFees($product, $finalPrice)
    {
        $productCategory = $product->taxonomies()->first();

        if ($productCategory?->fees) {
            $productFees = $productCategory->fees;

            return ($productFees / 100) * $finalPrice;
        } else {
            return $productCategory?->fixed_price ?? 0;
        }
    }

    private function productAmountToPay($product, $quantity, $couponResult)
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

    private function updateCouponUsage($couponId, $userId)
    {
        $coupon = Coupon::find($couponId);
        $coupon->usages()->updateOrCreate(
            ['user_id' => $userId],
            ['usage_count' => DB::raw('usage_count + 1')]
        );
    }
}
