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
            throw new \Exception(__('Coupon is not valid.'));
        }

        if (!$coupon->hasReachedMaxUsesForUser)
        {
            throw new \Exception(__('You have reached the maximum usage limit for this coupon.'));
        }

        $validProducts = collect();
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            if ($product && $coupon->isCouponValid($product,$user)) {
                $validProducts->push($product);
            }
        }

        if ($validProducts->isEmpty()) {
            throw new \Exception(__('Coupon is not applicable to the selected products.'));
        }

        return [
            'coupon' => $coupon,
            'discount_percentage' => $coupon->discount,
            'discount_source' => $coupon->discount_source,
            'valid_products' => $validProducts,
        ];
    }
}

