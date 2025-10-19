<?php

use App\Http\Controllers\Api\User\V3_1\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);
Route::get('/{id}', [PostController::class, 'single']);

Route::group(['middleware' => 'auth:api','as'=>'posts.'], function () {
    Route::post('/', [PostController::class, 'store']);
    Route::put('/{id}', [PostController::class, 'update']);
    Route::delete('/{id}', [PostController::class, 'delete']);
    Route::post('/{post}/vote', [PostController::class, 'vote']);
});

