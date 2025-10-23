<?php

use App\Http\Controllers\Api\User\V3_1\BkamConsultationController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BkamConsultationController::class, 'index']);
Route::get('/{id}', [BkamConsultationController::class, 'show']);
Route::post('/', [BkamConsultationController::class, 'store']);
Route::delete('/{id}', [BkamConsultationController::class, 'delete']);