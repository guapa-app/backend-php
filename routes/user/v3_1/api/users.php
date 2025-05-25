<?php

use App\Http\Controllers\Api\User\V3_1\AuthController;
use App\Http\Controllers\Api\User\V3_1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('referral-code', [UserController::class, 'getReferralCode']);
    Route::get('/profile', [UserController::class, 'single']);
    Route::post('/update-profile', [UserController::class, 'update']);
    Route::get('/country', [UserController::class, 'getCountry']);
    Route::put('/country', [UserController::class, 'updateCountry']);
});
