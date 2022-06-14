<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/', 'AddressController@index');
	Route::get('/{id}', 'AddressController@single');
	Route::post('/', 'AddressController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'AddressController@update');
    Route::delete('/{id}', 'AddressController@delete');
});
