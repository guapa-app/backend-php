<?php

use App\Http\Controllers\Api\V3\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);
Route::get('/{id}', [PostController::class, 'single']);
