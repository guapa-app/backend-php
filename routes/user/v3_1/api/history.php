<?php

use App\Http\Controllers\Api\HistoryController as ApiHistoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [ApiHistoryController::class, 'index']);
    Route::get('/{id}', [ApiHistoryController::class, 'single']);
    Route::post('/', [ApiHistoryController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}', [ApiHistoryController::class, 'update']);
    Route::delete('/{id}', [ApiHistoryController::class, 'delete']);
});
