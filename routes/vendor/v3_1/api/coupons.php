<?php

use App\Http\Controllers\Api\Vendor\V3_1\CouponController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'coupons.'], function () {
    Route::get('/', [CouponController::class, 'index']);
    Route::post('/', [CouponController::class, 'store']);
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);
    Route::delete('/{id}', [CouponController::class, 'destroy']);
});
