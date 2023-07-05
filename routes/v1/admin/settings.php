<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
    Route::post('/', 'SettingController@create');
    Route::get('/', 'SettingController@settings');
    Route::put('/{id}', 'SettingController@update');
});
