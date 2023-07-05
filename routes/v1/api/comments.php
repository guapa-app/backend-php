<?php

use App\Http\Controllers\Api\CommentController as ApiCommentController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                             [ApiCommentController::class, 'index']);
Route::get('/{id}',                                         [ApiCommentController::class, 'single']);

Route::group(['middleware' => 'auth:api'], function() {
	Route::post('/',                                        [ApiCommentController::class, 'create']);
	Route::match(['put', 'patch', 'post'], '/{id}',[ApiCommentController::class, 'update']);
	Route::delete('/{id}',                                  [ApiCommentController::class, 'delete']);
});
