<?php

use App\Http\Controllers\Api\User\V3_1\VendorController;
use App\Http\Controllers\Api\Vendor\V3_1\VendorSocialMediaController;
use App\Http\Controllers\Api\User\V3_1\ConsultationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VendorController::class, 'index']);
Route::get('/{id}', [VendorController::class, 'single']);

// get available time slots for a vendor
Route::get('/{vendor}/available-times/{date}', [ConsultationController::class, 'getAvailableTimeSlots']);

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/', [VendorController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}', [VendorController::class, 'update']);

    Route::apiResource('{vendor}/social-media', VendorSocialMediaController::class)->only([
        'store', 'update', 'destroy'
    ])->names('user.v3_1.vendors.social-media');
});
