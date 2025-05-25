<?php

use App\Http\Controllers\Api\V2\SupportMessageController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api', 'as' => 'support.'], function () {
    Route::post('/contact',                 [SupportMessageController::class, 'create']);
});
