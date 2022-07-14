<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'AuthController@register')->name('auth.register');
Route::post('login', 'AuthController@login')->name('auth.login');
Route::post('verify', 'AuthController@verify')->name('auth.verify');
Route::post('refresh_token', 'AuthController@refreshToken')->name('auth.refresh');
Route::post('send-otp', 'AuthController@sendSinchOtp')->name('auth.send-otp');
Route::post('check-phone', 'AuthController@checkIfPhoneExist')->name('auth.check-phone');
Route::post('verify-otp', 'AuthController@verifySinchOtp')->name('auth.verify-otp');

Route::group(['middleware' => 'auth:api'], function () {
    Route::delete('logout', 'AuthController@logout')->name('auth.logout');
    Route::get('user', 'AuthController@user');
    Route::delete('delete', 'AuthController@deleteAccount')->name('auth.delete');
});
