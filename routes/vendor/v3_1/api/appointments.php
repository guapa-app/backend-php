<?php

use App\Http\Controllers\Api\Vendor\V3_1\AppointmentOfferController;
use Illuminate\Support\Facades\Route;

Route::get('/offers', [AppointmentOfferController::class, 'index']);
Route::post('/offers', [AppointmentOfferController::class, 'store']);
