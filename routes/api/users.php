<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/{id}', 'UserController@single');
	Route::match(['put', 'patch', 'post'], '/{id}', 'UserController@update')->name('users.update');
});
