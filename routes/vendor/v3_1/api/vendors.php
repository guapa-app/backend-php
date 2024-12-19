<?php

use App\Http\Controllers\Api\Vendor\V3_1\InfluencerController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/update-profile', [VendorController::class, 'update'])->name('update');
    Route::put('/activate-wallet', [VendorController::class, 'activateWallet'])->name('activate-wallet');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ]);
    Route::apiResource('influencers', InfluencerController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});
