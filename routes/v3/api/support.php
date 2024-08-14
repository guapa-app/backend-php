<?php

use App\Http\Controllers\Api\V3\SupportMessageController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api', 'as' => 'support.'], function () {
    Route::post('/',                 [SupportMessageController::class, 'create'])->name('create');
    Route::get('/',                  [SupportMessageController::class, 'index'])->name('index');
    Route::get('/{id}',              [SupportMessageController::class, 'single'])->name('single');
});
