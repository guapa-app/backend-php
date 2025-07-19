<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\SupportMessageController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/types', [SupportMessageController::class, 'types']);
    Route::get('/', [SupportMessageController::class, 'index']);
    Route::post('/', [SupportMessageController::class, 'create'])->name('v3_1.vendor.support.create');
    Route::get('/{id}', [SupportMessageController::class, 'single']);
});
