<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\V3_1\ConsultationController;

Route::middleware('auth:api')->group(function () {
    Route::get('/', [ConsultationController::class, 'index']);
    Route::post('/', [ConsultationController::class, 'store']);
    Route::get('/{id}', [ConsultationController::class, 'show']);
    Route::post('/{id}/cancel', [ConsultationController::class, 'cancel']);
});
