<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:admin'], function() {
	Route::get('/', 'CommentController@index');
	Route::get('/{id}', 'CommentController@single');
	Route::post('/', 'CommentController@create');
    Route::match(['put', 'patch', 'post'], '/{id}', 'CommentController@update');
    Route::delete('/{id}', 'CommentController@delete');
});