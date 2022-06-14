<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/', 'StaffController@index')->name('staff.list');
	Route::post('/', 'StaffController@create')->name('staff.create');
	Route::match(['put', 'patch', 'post'], '/{id}', 'StaffController@update')->name('staff.update');
	Route::delete('/{userId}/{vendorId}', 'StaffController@delete')->name('staff.delete');
});
