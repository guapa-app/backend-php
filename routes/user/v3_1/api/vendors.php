<?php

use App\Http\Controllers\Api\User\V3_1\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VendorController::class, 'index'])->name('list');
Route::get('/{id}', [VendorController::class, 'single'])->name('vendors.show');
