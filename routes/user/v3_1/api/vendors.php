<?php

use App\Http\Controllers\Api\User\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VendorController::class, 'index'])->name('list');
Route::get('/{id}', [VendorController::class, 'single'])->name('vendors.show');

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('create');
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update'])->name('update');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ]);
});
