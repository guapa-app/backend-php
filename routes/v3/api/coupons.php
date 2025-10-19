<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\CouponController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/',                                             [CouponController::class, 'index']);
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('v3.coupons.apply');
});
