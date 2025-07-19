<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\GiftCardController;

Route::prefix('gift-cards')->group(function () {
    Route::get('/', [GiftCardController::class, 'index']);
    Route::post('/', [GiftCardController::class, 'store']);
    Route::get('/statistics', [GiftCardController::class, 'statistics']);
    Route::get('/options', [GiftCardController::class, 'options']);
    Route::get('/code', [GiftCardController::class, 'getByCode']);
    Route::post('/bulk-update-status', [GiftCardController::class, 'bulkUpdateStatus']);
    Route::get('/{id}', [GiftCardController::class, 'show']);
    Route::put('/{id}', [GiftCardController::class, 'update']);
    Route::delete('/{id}', [GiftCardController::class, 'destroy']);
});