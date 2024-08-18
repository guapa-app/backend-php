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
        $totalFees = 0;
        $totalDiscountFees = 0;
        $totalDiscountAmount = 0;
        $productDiscounts = [];

        foreach ($products as $product) {
            $price = $this->getDiscountedPrice($product);
            $inputItem = Arr::first($requestData['products'], fn($value) => (int) $value['id'] === $product->id);
            $quantity = $inputItem['quantity'] ?? 1;

            $finalPrice = $price * $quantity;
            $productFees = $this->calculateProductFees($product, $finalPrice);

            $totalAmount += $finalPrice;
            $totalFees += $productFees;

            // Calculate discount for this product
            $discountResult = $this->applyDiscount($finalPrice, $coupon->discount_percentage, $coupon->discount_source, $productFees);

            $productDiscounts[$product->id] = [
                'total' => $discountResult['total'],
                'fees' => $discountResult['fees'],
                'discount_amount' => $discountResult['discountAmount'],
            ];

            $totalDiscountAmount += $discountResult['discountAmount'];
            $totalDiscountFees += $discountResult['fees'];

        }

        return [
            'total' => $totalAmount - $totalDiscountAmount,
            'fees' => $totalDiscountFees,
            'remaining' => ( $totalAmount - $totalDiscountAmount) - $totalDiscountFees ,
            'discount_amount' => $totalDiscountAmount,
            'fees_before_discount' => $totalFees,
            'total_before_discount' => $totalAmount,
            'product_discounts' => $productDiscounts,

        ];
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

    public function applyDiscount($totalAmount, $discountPercentage, $discountSource, $fees)
    {
        $newTotal = $totalAmount;
        $newFees = $fees;
        $discountAmount = 0;

        switch ($discountSource) {
            case 'vendor':
                $amountAfterFees = $totalAmount - $fees;
                $discountAmount = ($amountAfterFees * $discountPercentage) / 100;
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


    public function delete(int $id)
    {
        $coupon = $this->couponRepository->getOneOrFail($id);

        if (!is_null($coupon->admin_id)) {
            abort(403, "You can't delete this coupon");
        }
        $this->couponRepository->delete($coupon->id);
    }
}
