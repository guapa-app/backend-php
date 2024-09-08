<?php

use App\Http\Controllers\Api\Vendor\V3_1\VendorClientController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update'])->name('update');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ]);
});
