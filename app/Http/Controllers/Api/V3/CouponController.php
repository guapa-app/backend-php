<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\CouponController as ApiCouponController;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;


class CouponController extends ApiCouponController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return CouponCollection::make(parent::index($request))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function store(CouponRequest $request)
    {
        $coupon = parent::store($request);
        return CouponResource::make($coupon)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function destroy(Coupon $coupon)
    {
        $result = parent::destroy($coupon);
        if ($result['status']) {
            return $this->successJsonRes([], __('api.deleted'));
        } else {
            return $this->errorJsonRes([],$result['error']);
        }
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {
        $result = parent::applyCoupon($request);
        if ($result['status']) {
            return $this->successJsonRes($result['data'], __('Coupon applied successfully'));
        } else {
            return $this->errorJsonRes([],$result['error']);
        }
    }
}
