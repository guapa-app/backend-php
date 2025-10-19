<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\InfluencerController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [VendorController::class, 'index']);
    Route::post('/', [VendorController::class, 'create'])->name('v3_1.vendor.vendors.create');
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update'])->name('v3_1.vendor.vendors.update');
    Route::delete('/{id}', [VendorController::class, 'delete'])->name('v3_1.vendor.vendors.delete');

    Route::put('/activate-wallet', [VendorController::class, 'activateWallet'])->name('v3_1.vendor.vendors.activate-wallet');

    Route::match(['put', 'post'],'/working-days', [VendorController::class, 'updateWorkingDays'])->name('v3_1.vendor.vendors.update-working-days');

    Route::get('/working-days', [VendorController::class, 'getWorkingDays'])->name('v3_1.vendor.vendors.get-working-days');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ])->names([
        'store' => 'v3_1.vendor.vendors.social-media.store',
        'update' => 'v3_1.vendor.vendors.social-media.update',
        'destroy' => 'v3_1.vendor.vendors.social-media.destroy'
    ]);
    Route::apiResource('influencers', InfluencerController::class)->only([
        'index', 'store', 'update', 'destroy'
    ])->names([
        'index' => 'v3_1.vendor.vendors.influencers.index',
        'store' => 'v3_1.vendor.vendors.influencers.store',
        'update' => 'v3_1.vendor.vendors.influencers.update',
        'destroy' => 'v3_1.vendor.vendors.influencers.destroy'
    ]);
});
