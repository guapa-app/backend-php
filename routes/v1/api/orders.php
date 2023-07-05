<?php

use App\Http\Controllers\Api\OrderController as ApiOrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                             [ApiOrderController::class, 'index']);
	Route::get('/{id}',                                         [ApiOrderController::class, 'single']);
	Route::post('/',                                            [ApiOrderController::class, 'create']);
    Route::post('{id}/print-pdf',                               [ApiOrderController::class, 'printPDF']);
    Route::match(['put', 'patch', 'post'], '/{id}',    [ApiOrderController::class, 'update']);
});
