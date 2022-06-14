<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'CityController@index');
	Route::get('/{id}', 'CityController@single');
	Route::post('/', 'CityController@create');
    Route::put('/{id}', 'CityController@update');
    Route::post('/{id}', 'CityController@update');
    Route::delete('/{id}', 'CityController@delete');
});
