<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'VendorController@index')->name('list');
Route::get('/{id}', 'VendorController@single')->name('single');

Route::group(['middleware' => 'auth:api', 'as' => 'venors.'], function() {
	Route::post('/', 'VendorController@create')->name('create');
	Route::match(['put', 'patch', 'post'], '/{id}', 'VendorController@update')->name('update');
	Route::post('/{id}/share', 'VendorController@share')->name('share');
});
