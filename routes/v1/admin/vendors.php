<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'VendorController@index');
	Route::get('/{id}', 'VendorController@single');
	Route::post('/', 'VendorController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'VendorController@update');
    Route::delete('/{id}', 'VendorController@delete');
});