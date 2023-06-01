<?php

use App\Http\Controllers\Api\ReviewController as ApiReviewController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
	Route::get('/',                                        [ApiReviewController::class, 'index']);
	Route::post('/',                                       [ApiReviewController::class, 'create']);
});
