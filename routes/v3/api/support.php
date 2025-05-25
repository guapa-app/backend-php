<?php

use App\Http\Controllers\Api\V3\SupportMessageController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api', 'as' => 'support.'], function () {
    Route::get('/types',             [SupportMessageController::class, 'types']);
    Route::post('/',                 [SupportMessageController::class, 'create']);
    Route::get('/',                  [SupportMessageController::class, 'index']);
    Route::get('/{id}',              [SupportMessageController::class, 'single']);
});
