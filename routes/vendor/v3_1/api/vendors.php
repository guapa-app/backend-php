<?php

use App\Http\Controllers\Api\Vendor\V3_1\InfluencerController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/update-profile', [VendorController::class, 'update'])->name('update');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ]);
    Route::apiResource('{vendor}/influencers', InfluencerController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});
