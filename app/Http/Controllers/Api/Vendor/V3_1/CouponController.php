<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\CouponController as ApiCouponController;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\CouponRequest;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
<<<<<<< HEAD
<<<<<<< HEAD
use App\Models\Coupon;
use Illuminate\Http\Request;


=======
use Illuminate\Http\Request;

>>>>>>> refactor/favorite-addresss
=======
use Illuminate\Http\Request;

>>>>>>> bc42c8a48fdc214d6b0494e77c6ddda120cf383b
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
<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> refactor/favorite-addresss
=======

>>>>>>> bc42c8a48fdc214d6b0494e77c6ddda120cf383b
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
