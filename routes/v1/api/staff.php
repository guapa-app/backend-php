<?php

use App\Http\Controllers\Api\V1\StaffController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [StaffController::class, 'index'])->name('v1.staff.list');
	Route::post('/',                                        [StaffController::class, 'create'])->name('v1.staff.create');
	Route::match(['put', 'patch', 'post'], '/{id}',[StaffController::class, 'update'])->name('v1.staff.update');
	Route::delete('/{userId}/{vendorId}',                   [StaffController::class, 'delete'])->name('v1.staff.delete');
});
