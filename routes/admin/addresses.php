<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'AddressController@index');
	Route::get('/{id}', 'AddressController@single');
	Route::post('/', 'AddressController@create');
    Route::put('/{id}', 'AddressController@update');
    Route::post('/{id}', 'AddressController@update');
    Route::delete('/{id}', 'AddressController@delete');
});
