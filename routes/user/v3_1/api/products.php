<?php

use App\Http\Controllers\Api\User\V3_1\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index']);
Route::get('/{id}', [ProductController::class, 'single']);
