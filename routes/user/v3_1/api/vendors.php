<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\V3_1\VendorController;
use App\Http\Controllers\Api\User\V3_1\ConsultationController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;

Route::get('/', [VendorController::class, 'index'])->name('v3_1.vendors.list');
Route::get('/{id}', [VendorController::class, 'single'])->name('v3_1.vendors.show');

// get available time slots for a vendor
Route::get('/{vendor}/available-times/{date}', [ConsultationController::class, 'getAvailableTimeSlots'])->name('v3_1.vendors.available-time-slots');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/', [VendorController::class, 'create'])->name('v3_1.vendors.create');
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update'])->name('v3_1.vendors.update');

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ])->names([
        'store' => 'v3_1.vendors.social-media.store',
        'update' => 'v3_1.vendors.social-media.update',
        'destroy' => 'v3_1.vendors.social-media.destroy'
    ]);
});
