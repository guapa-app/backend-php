<?php

use App\Http\Controllers\Api\Vendor\V3_1\VendorClientController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::match(['put', 'patch', 'post'], '/', [VendorController::class, 'update'])->name('update');

    Route::apiResource('{vendor}/clients',      VendorClientController::class)->only(['index', 'store', 'destroy']);
    Route::get('/{vendor}/clients/{client}/orders', [VendorClientController::class, 'getClientOrders'])->name('client.orders');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only(['store', 'update', 'destroy']);
});
