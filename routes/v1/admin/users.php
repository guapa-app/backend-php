<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'UserController@index');
	Route::get('/{id}', 'UserController@single');
	Route::post('/', 'UserController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'UserController@update');
    Route::delete('/{id}', 'UserController@delete');
});