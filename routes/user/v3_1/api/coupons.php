<?php

use App\Http\Controllers\Api\User\V3_1\CouponController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'coupons.'], function () {
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);
});
