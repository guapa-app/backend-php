<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\CouponController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [CouponController::class, 'index'])->name('v3_1.vendor.coupons.index');
    Route::post('/', [CouponController::class, 'store'])->name('v3_1.vendor.coupons.store');
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('v3_1.vendor.coupons.apply');
    Route::delete('/{id}', [CouponController::class, 'destroy'])->name('v3_1.vendor.coupons.destroy');
});
