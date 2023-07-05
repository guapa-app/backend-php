<?php

use App\Http\Controllers\Api\PostController as ApiPostController;
use Illuminate\Support\Facades\Route;

Route::get('/',                     [APiPostController::class, 'index']);
Route::get('/{id}',                 [APiPostController::class, 'single']);
