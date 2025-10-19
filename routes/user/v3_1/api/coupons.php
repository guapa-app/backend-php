<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\V3_1\CouponController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/', [CouponController::class, 'index']);
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('v3_1.coupons.apply');
});
