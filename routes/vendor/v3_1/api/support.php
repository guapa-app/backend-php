<?php

use App\Http\Controllers\Api\Vendor\V3_1\SupportMessageController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api', 'as' => 'support.'], function () {
    Route::get('/types', [SupportMessageController::class, 'types'])->name('types');
    Route::post('/', [SupportMessageController::class, 'create'])->name('create');
    Route::get('/', [SupportMessageController::class, 'index'])->name('index');
    Route::get('/{id}', [SupportMessageController::class, 'single'])->name('single');
});
