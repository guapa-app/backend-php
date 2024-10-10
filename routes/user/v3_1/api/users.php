<?php

use App\Http\Controllers\Api\User\V3_1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('referral-code', [UserController::class, 'getReferralCode']);
    Route::get('/{id}', [UserController::class, 'single']);
    Route::post('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::put('/change-phone', [UserController::class, 'updatePhone'])->name('users.update_phone');
});
