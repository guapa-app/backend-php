<?php

use App\Http\Controllers\Api\Vendor\V3_1\StaffController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [StaffController::class, 'index'])->name('staff.list');
    Route::post('/', [StaffController::class, 'create'])->name('staff.create');
    Route::match(['put', 'patch', 'post'], '/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/{userId}', [StaffController::class, 'delete'])->name('staff.delete');
});
