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
