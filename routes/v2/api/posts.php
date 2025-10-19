<?php

use App\Http\Controllers\Api\V2\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/',                     [PostController::class, 'index']);
Route::get('/{id}',                 [PostController::class, 'single']);
