<?php

use App\Http\Controllers\Api\User\V3_1\AppointmentFormController;
use App\Http\Controllers\Api\User\V3_1\AppointmentOfferController;
use Illuminate\Support\Facades\Route;

Route::get('/form', [AppointmentFormController::class, 'index']);
Route::prefix('offers')->controller(AppointmentOfferController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'store');
    Route::post('/accept', 'accept');
    Route::post('/reject', 'reject');
});
