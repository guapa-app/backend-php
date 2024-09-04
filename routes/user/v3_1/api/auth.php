<?php

use App\Http\Controllers\Api\User\V3_1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('refresh_token', [AuthController::class, 'refreshToken'])->name('auth.refresh');
Route::post('send-otp', [AuthController::class, 'sendOtp'])->name('auth.send-otp');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('user', [AuthController::class, 'user']);
    Route::delete('delete', [AuthController::class, 'deleteAccount'])->name('auth.delete');
});
