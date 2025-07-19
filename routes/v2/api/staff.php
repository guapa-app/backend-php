<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\StaffController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/',                                         [StaffController::class, 'index'])->name('v2.staff.list');
    Route::post('/',                                        [StaffController::class, 'create'])->name('v2.staff.create');
    Route::match(['put', 'patch', 'post'], '/{id}',         [StaffController::class, 'update'])->name('v2.staff.update');
    Route::delete('/{userId}/{vendorId}',                   [StaffController::class, 'delete'])->name('v2.staff.delete');
});
