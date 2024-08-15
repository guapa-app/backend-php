<?php

namespace App\Services\V3;

use App\Enums\ProductType;
use App\Models\Appointment;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CouponService;
use App\Services\PaymentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Contracts\Repositories\OrderRepositoryInterface;

use \App\Services\OrderService as BaseOrderService;

class OrderService extends BaseOrderService
{
    protected $couponService;

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
                $couponResult = $this->couponService->applyCoupon($data['coupon_code'], $productIds);
                if (!$couponResult['status']) {
                    throw new \Exception($couponResult['error']);
                }
            }

            $productsTitles = $products->pluck('title')->implode(' - ');

            // Group products by vendor to create an order for each vendor
            $keyedProducts = $products->groupBy('vendor_id');

            $orders = new Collection;
            $now = now();

            foreach ($keyedProducts as $vendorId => $vendorProducts) {
                $data['vendor_id'] = $vendorId;
                $this->fees = 0; // Reset fees for each vendor
                $discountAmount = 0; // Initialize discount amount

                $data['total'] = (float) $vendorProducts->sum(function ($product) use ($data, $couponResult, &$discountAmount) {
                    $inputItem = Arr::first($data['products'], fn($value) => (int) $value['id'] === $product->id);

                    if ($product->offer) {
                        $product->price -= ($product->price * ($product->offer->discount / 100));
                        $product->price = round($product->price, 2);
                    }

                    $finalPrice = $inputItem['quantity'] * $product->price;

                    // Calculate initial fees
                    $productFees = $this->calculateProductFees($product, $finalPrice);

                    // Apply coupon discount if applicable
                    if ($couponResult && $couponResult['data']['valid_products']->contains($product->id)) {
                        $productDiscountAmount = ($finalPrice * $couponResult['data']['discount_percentage']) / 100;
                        $discountAmount += $productDiscountAmount;
                        $finalPrice -= $productDiscountAmount;
                    }

                    // Adjust fees based on discount source
                    if ($couponResult) {
                        $discountSource = $couponResult['data']['discount_source'];
                        $this->adjustFeesBasedOnDiscountSource($productFees, $productDiscountAmount, $discountSource);
                    } else {
                        $this->fees += $productFees;
                    }

                    return $finalPrice;
                });
                $orderData = $data;
                if ($couponResult) {
                    $orderData['coupon_id'] = $couponResult['data']['coupon_id'];
                    $orderData['discount_amount'] = $discountAmount;
                }
                $order = $this->repository->create($orderData);

                $items = $vendorProducts->map(function ($product) use ($data, $order, $now, $vendorId) {
                    $inputItem = Arr::first($data['products'], fn($value) => (int) $value['id'] === $product->id);

                    if (isset($inputItem['appointment'])) {
                        $appointment = Appointment::find($inputItem['appointment']['id']);

                        if ($appointment->vendor_id !== $vendorId) {
                            $order->delete();
                            abort(400, 'Invalid appointment selected');
                        }

                        $inputItem['appointment']['from_time'] = $appointment->from_time;
                        $inputItem['appointment']['to_time'] = $appointment->to_time;
                    }

                    return [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'offer_id' => optional($product->offer)->id,
                        'amount' => $product->price,
                        'quantity' => $inputItem['quantity'],
                        'appointment' => isset($inputItem['appointment']) ? json_encode($inputItem['appointment']) : null,
                        'user_id' => $inputItem['staff_user_id'] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                });

                // Check that provided user ids belong to vendor
                $userIds = $items->pluck('user_id')->filter()->all();

                if (!$this->checkVendorUsers($vendorId, $userIds)) {
                    abort(400, 'Invalid staff provided for vendor');
                }

                OrderItem::insert($items->toArray());

                $order->load('items', 'address', 'user', 'vendor');
                $orders->push($order);

                if ($this->isService) {
                    $this->taxes = ($this->taxesPercentage / 100) * $this->fees;

                    // Generate invoice for the first order (since there's only one order per vendor)
                    $invoice = $this->paymentService->generateInvoice($orders, $productsTitles, $this->fees, $this->taxes);
                    $orders->first()->invoice_url = $invoice->url;
                }

                // Update coupon usage if a coupon was applied
                if ($couponResult) {
                    $coupon = Coupon::find($couponResult['data']['coupon_id']);
                    $coupon->usages()->updateOrCreate(
                        ['user_id' => $data['user_id']],
                        ['usage_count' => DB::raw('usage_count + 1')]
                    );
                }
            }
            return $orders;
        });
    }
    private function calculateProductFees($product, $finalPrice)
    {
        if ($product->type === ProductType::Service) {
            $this->isService = true;
            $productFees = optional($product->categories()->first())->fees ?? 0;
            return ($productFees / 100) * $finalPrice;
        } else {
            return ($this->productFees / 100) * $finalPrice;
        }
    }

    private function adjustFeesBasedOnDiscountSource($productFees, $productDiscountAmount, $discountSource)
    {
        switch ($discountSource) {
            case 'vendor':
                $this->fees += $productFees; // Calculate fees before applying discount
                break;
            case 'app':
                $this->fees += max(0, $productFees - $productDiscountAmount); // Fees minus discount amount, but not less than 0
                break;
            case 'both':
                $this->fees += max(0, $productFees - ($productDiscountAmount /2 )); // Half of the discount amount
                break;
            default:
                $this->fees += $productFees; // Default case, no adjustment
        }
    }
}


