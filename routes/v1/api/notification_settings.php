<?php

use App\Http\Controllers\Api\NotificationSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/notification-settings', [NotificationSettingController::class, 'index']);
    Route::get('/notification-settings/{id}', [NotificationSettingController::class, 'show']);
    Route::post('/notification-settings', [NotificationSettingController::class, 'store']);
    Route::put('/notification-settings/{id}', [NotificationSettingController::class, 'update']);
    Route::delete('/notification-settings/{id}', [NotificationSettingController::class, 'destroy']);
});
