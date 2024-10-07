<?php

use App\Http\Controllers\Api\Vendor\V3_1\DoctorController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'prefix' => '{vendor}/doctors', 'as' => 'vendors.doctors.'], function () {
    Route::get('/', [DoctorController::class, 'list'])->name('list');
    Route::get('/{doctor}', [DoctorController::class, 'show'])->name('show');
    Route::post('/', [DoctorController::class, 'add'])->name('create');
//    Route::match(['put', 'patch', 'post'], '/{doctor}', [DoctorController::class, 'update'])->name('update');
});
