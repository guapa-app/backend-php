<?php

namespace App\Services;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Collection;

class CouponService
{
    protected $couponRepository;

    public function __construct(CouponRepositoryInterface $couponRepository,)
    {
        $this->couponRepository = $couponRepository;
    }

    public function applyCoupon(string $couponCode, array $productIds)
    {
        $coupon = $this->couponRepository->findByCode($couponCode);
        $user = auth('api')->user();

        if (!$coupon || !$coupon->isActive()) {
            return [
                'status' => false,
                'error' => __('Coupon is not valid.'),
            ];
        }

        if ($coupon->hasReachedMaxUsesForUser($user))
        {
            return [
                'status' => false,
                'error' => __('You have reached the maximum usage limit for this coupon.'),
            ];
        }

        $validProducts = collect();
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            if ($product && $coupon->isCouponValid($product,$user)) {
                $validProducts->push($product->id);
            }
        }
        if ($validProducts->isEmpty()) {
            return [
                'status' => false,
                'error' => __('Coupon is not applicable to the selected products.'),
            ];
        }

        return [
            'status' => true,
            'data' => [
                'coupon_id' => $coupon->id,
                'discount_percentage' => $coupon->discount_percentage,
                'discount_source' => $coupon->discount_source,
                'valid_products' => $validProducts,
            ],
        ];
    }
}

