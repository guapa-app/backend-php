<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'OrderController@index');
	Route::get('/{id}', 'OrderController@single');
    Route::put('/{id}', 'OrderController@update');
    Route::delete('/{id}', 'OrderController@delete');
});
