<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\CouponController as ApiCouponController;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\User\V3_1\CouponCollection;
use App\Http\Resources\User\V3_1\CouponResource;
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

    public function destroy($id)
    {
        parent::destroy($id);

        return $this->successJsonRes([], __('api.deleted'));
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {
        $result = parent::applyCoupon($request);
        if ($result['status']) {
            return $this->successJsonRes($result['data'], __('Coupon applied successfully'));
        } else {
            return $this->errorJsonRes([], $result['error']);
        }
    }
}
