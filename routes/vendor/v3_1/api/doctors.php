<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\DoctorController;

Route::group(['middleware' => 'auth:api', 'prefix' => '{vendor}/doctors'], function () {
    Route::get('/', [DoctorController::class, 'list'])->name('v3_1.vendor.doctors.list');
    Route::get('/{doctor}', [DoctorController::class, 'show'])->name('v3_1.vendor.doctors.show');
    Route::post('/', [DoctorController::class, 'add'])->name('v3_1.vendor.doctors.create');
    Route::match(['put', 'patch', 'post'], '/{doctor}', [DoctorController::class, 'edit'])->name('v3_1.vendor.doctors.edit');
});
