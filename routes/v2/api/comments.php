<?php

use App\Http\Controllers\Api\V2\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                             [CommentController::class, 'index']);
Route::get('/{id}',                                         [CommentController::class, 'single']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/',                                        [CommentController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}',[CommentController::class, 'update']);
    Route::delete('/{id}',                                  [CommentController::class, 'delete']);
});
