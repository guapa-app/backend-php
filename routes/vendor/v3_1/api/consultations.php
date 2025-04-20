<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\ConsultationController;

Route::middleware('auth:api')->group(function () {
    Route::get('/', [ConsultationController::class, 'index']);
    Route::get('/{consultation}', [ConsultationController::class, 'show']);

    Route::post('/{consultation}/reject', [ConsultationController::class, 'reject']);
});
