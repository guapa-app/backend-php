<?php

use App\Http\Controllers\Api\User\V3_1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('v3_1.auth.register');
Route::post('login', [AuthController::class, 'login'])->name('v3_1.auth.login');
Route::post('refresh_token', [AuthController::class, 'refreshToken'])->name('v3_1.auth.refresh');
Route::post('send-otp', [AuthController::class, 'sendOtp'])->name('v3_1.auth.send-otp');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('v3_1.auth.verify-otp');

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout', [AuthController::class, 'logout'])->name('v3_1.auth.logout');
    Route::get('user', [AuthController::class, 'user']);
    Route::delete('delete', [AuthController::class, 'deleteAccount'])->name('v3_1.auth.delete');
    Route::post('/change-phone', [AuthController::class, 'updatePhone'])->name('v3_1.users.update_phone');
});
