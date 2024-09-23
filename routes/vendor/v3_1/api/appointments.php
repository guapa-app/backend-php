<?php

use App\Http\Controllers\Api\Vendor\V3_1\AppointmentOfferController;
use Illuminate\Support\Facades\Route;

Route::prefix('offers')->controller(AppointmentOfferController::class)
    ->middleware('auth:api')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
    });
