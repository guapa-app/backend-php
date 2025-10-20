<?php

use App\Http\Controllers\Api\User\V3_1\BkamConsultationController;
use Illuminate\Support\Facades\Route;


Route::resource('/', BkamConsultationController::class);