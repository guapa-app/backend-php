<?php

use App\Http\Controllers\Api\StaffController as ApiStaffController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                         [ApiStaffController::class, 'index'])->name('staff.list');
	Route::post('/',                                        [ApiStaffController::class, 'create'])->name('staff.create');
	Route::match(['put', 'patch', 'post'], '/{id}',[ApiStaffController::class, 'update'])->name('staff.update');
	Route::delete('/{userId}/{vendorId}',                   [ApiStaffController::class, 'delete'])->name('staff.delete');
});
