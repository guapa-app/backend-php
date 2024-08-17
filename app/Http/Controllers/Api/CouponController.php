<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private $couponRepository;
    private $couponService;

    public function __construct(CouponRepositoryInterface $couponRepository,CouponService $couponService)
    {
        $this->couponRepository = $couponRepository;
        $this->couponService = $couponService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $coupons = $this->couponRepository->all($request);
        return $coupons;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        $data = $request->validated();
        $data['vendors'][] = $request->input('vendor_id');
        return $this->couponRepository->create($data);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        if ($coupon->admin_id) {
            return response()->json(['error' => 'Cant delete Coupons added by admin'], 403);
        }

        return $this->couponRepository->destroy($coupon);
    }
    /**
     * Apply coupon to products
     */
    public function applyCoupon(ApplyCouponRequest $request)
    {
        $couponCode = $request->input('coupon_code');
        $requestData = [
            'products' => $request->input('products'),
        ];
        $result = $this->couponService->applyCoupon($couponCode, $requestData);
        return $result;
    }
}
