<?php

use App\Http\Controllers\Api\User\V3_1\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AppointmentController::class, 'index']);
