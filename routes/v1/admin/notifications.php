<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
    Route::get('/users', 'DatabaseNotificationController@users');
    Route::get('/vendors', 'DatabaseNotificationController@vendors');
    Route::get('/', 'DatabaseNotificationController@index');
	Route::get('/{id}', 'DatabaseNotificationController@single');
	Route::post('/', 'DatabaseNotificationController@create');
    Route::put('/{id}', 'DatabaseNotificationController@update');
    Route::post('/{id}', 'DatabaseNotificationController@update');
    Route::delete('/{id}', 'DatabaseNotificationController@delete');
});
