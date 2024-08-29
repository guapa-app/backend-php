<?php

use App\Http\Controllers\Api\User\V3_1\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);
Route::get('/{id}', [PostController::class, 'single']);
