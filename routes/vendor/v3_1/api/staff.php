<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\StaffController;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [StaffController::class, 'index'])->name('v3_1.vendor.staff.list');
    Route::post('/', [StaffController::class, 'create'])->name('v3_1.vendor.staff.create');
    Route::match(['put', 'patch', 'post'], '/{id}', [StaffController::class, 'update'])->name('v3_1.vendor.staff.update');
    Route::delete('/{userId}', [StaffController::class, 'delete'])->name('v3_1.vendor.staff.delete');
});
