<?php

use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/',                                             [OrderController::class, 'index']);
    Route::get('/{id}',                                         [OrderController::class, 'single']);
    Route::post('/',                                            [OrderController::class, 'create']);
    Route::post('{id}/print-pdf',                               [OrderController::class, 'printPDF']);
    Route::match(['put', 'patch', 'post'], '/{id}',    [OrderController::class, 'update']);
});
