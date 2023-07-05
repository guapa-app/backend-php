<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'SupportMessageController@index');
	Route::get('/{id}', 'SupportMessageController@single');
    Route::put('/{id}', 'SupportMessageController@update');
    Route::delete('/{id}', 'SupportMessageController@delete');
});
