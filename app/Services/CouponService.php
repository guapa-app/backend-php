<?php

namespace App\Services;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;

class CouponService
{
    protected $couponRepository;

    public function __construct(CouponRepositoryInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    public function applyCoupon(string $couponCode, array $requestData, $calculateTotals = true)
    {
        $productIds = array_column($requestData['products'], 'id');
        $coupon = $this->couponRepository->findByCode($couponCode);
        $user = auth('api')->user();

        if (!$coupon || !$coupon->isActive()) {
            return [
                'status' => false,
                'error' => __('Coupon is not valid.'),
            ];
        }

        if ($coupon->hasReachedMaxUsesForUser($user)) {
            return [
                'status' => false,
                'error' => __('You have reached the maximum usage limit for this coupon.'),
            ];
        }

        $validProducts = collect();
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            if ($product && $coupon->isCouponValid($product, $user)) {
                $validProducts->push($product);
            }
        }

        if ($validProducts->isEmpty()) {
            return [
                'status' => false,
                'error' => __('Coupon is not applicable to the selected products.'),
            ];
        }

        $result = [
            'status' => true,
            'data' => [
                'coupon_id' => $coupon->id,
                'discount_percentage' => $coupon->discount_percentage,
                'discount_source' => $coupon->discount_source,
                'valid_products' => $validProducts->pluck('id'),
            ],
        ];

        if ($calculateTotals) {
            $result['data'] = array_merge($result['data'], $this->calculateTotals($validProducts, $coupon, $requestData));
        }

        return $result;
    }

    private function calculateTotals(Collection $products, $coupon, array $requestData)
    {
        $totalAmount = 0;
        $fees = 0;
        $discountAmount = 0;

        foreach ($products as $product) {
            $price = $product->price;
            if ($product->offer) {
                $price -= ($price * ($product->offer->discount / 100));
                $price = round($price, 2);
            }

            $inputItem = Arr::first($requestData['products'], fn($value) => (int) $value['id'] === $product->id);
            $quantity = $inputItem['quantity'] ?? 1;

            $finalPrice = $price * $quantity;
            $totalAmount += $finalPrice;

            // Calculate product fees
            $productFees = $this->calculateProductFees($product, $finalPrice);
            $fees += $productFees;

            // Apply coupon discount
            if ($coupon->discount_source !== 'app') {
                $productDiscountAmount = ($finalPrice * $coupon->discount_percentage) / 100;
                $discountAmount += $productDiscountAmount;
            }
        }

        // Apply discount based on the discount source
        $discountResult = $this->applyDiscount($totalAmount, $coupon->discount_percentage, $coupon->discount_source, $fees);

        return [
            'total' => $discountResult['total'],
            'fees' => $discountResult['fees'],
            'discount_amount' => $discountResult['discountAmount'],
        ];
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

    public function applyDiscount($totalAmount, $discountPercentage, $discountSource, $fees)
    {
        $newTotal = $totalAmount;
        $newFees = $fees;
        $discountAmount = 0;

        switch ($discountSource) {
            case 'vendor':
                $discountAmount = ($totalAmount * $discountPercentage) / 100;
                $newTotal -= $discountAmount;
                break;
            case 'app':
                if ($newFees > 0) {
                    $discountAmount = ($newFees * $discountPercentage) / 100;
                    $newFees -= $discountAmount;
                    $newTotal -= $discountAmount;
                }
                break;
            case 'both':
                $discountAmount = ($totalAmount * $discountPercentage) / 100;
                $newTotal -= $discountAmount;
                $newFees -= ($newFees * $discountPercentage) / 100;
                break;
        }

        return [
            'total' => $newTotal,
            'fees' => $newFees,
            'discountAmount' => $discountAmount
        ];
    }
}
