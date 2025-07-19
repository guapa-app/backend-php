<?php

use App\Http\Controllers\Api\V1\Admin\GiftCardBackgroundController;
use Illuminate\Support\Facades\Route;

Route::prefix('gift-card-backgrounds')->group(function () {
    Route::get('/', [GiftCardBackgroundController::class, 'index']);
    Route::post('/', [GiftCardBackgroundController::class, 'store']);
    Route::get('/active', [GiftCardBackgroundController::class, 'active']);
    Route::get('/{id}', [GiftCardBackgroundController::class, 'show']);
    Route::put('/{id}', [GiftCardBackgroundController::class, 'update']);
    Route::delete('/{id}', [GiftCardBackgroundController::class, 'destroy']);
    Route::patch('/{id}/toggle-status', [GiftCardBackgroundController::class, 'toggleStatus']);
});
