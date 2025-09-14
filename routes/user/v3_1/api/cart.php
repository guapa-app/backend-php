<?php

use App\Http\Controllers\Api\User\V3_1\CartController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/', [CartController::class, 'getCart'])->name('v3_1.cart.get');
    Route::post('/add', [CartController::class, 'addToCart'])->name('v3_1.cart.add');
    Route::post('/remove', [CartController::class, 'removeFromCart'])->name('v3_1.cart.remove');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('v3_1.cart.clear');
    Route::post('/increment', [CartController::class, 'incrementQuantity'])->name('v3_1.cart.increment');
    Route::post('/decrement', [CartController::class, 'decrementQuantity'])->name('v3_1.cart.decrement');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('v3_1.cart.checkout');
});