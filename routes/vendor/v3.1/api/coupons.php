<?php

use App\Http\Controllers\Api\Vendor\V3_1\CouponController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'coupons.'], function () {
    Route::get('/', [CouponController::class, 'index'])->name('index');
    Route::post('/', [CouponController::class, 'store'])->name('store');
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply');
    Route::delete('/{id}', [CouponController::class, 'destroy'])->name('destroy');
});
