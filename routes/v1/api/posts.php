<?php

use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/',                     [PostController::class, 'index']);
Route::get('/{id}',                 [PostController::class, 'single']);
