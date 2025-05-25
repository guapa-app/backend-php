<?php

use App\Http\Controllers\Api\V2\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                                             [VendorController::class, 'index']);
Route::get('/{id}',                                                         [VendorController::class, 'single']);

Route::group(['middleware' => 'auth:api', 'as' => 'vendors.'], function () {
    Route::post('/',                                                        [VendorController::class, 'create']);
    Route::match(['put', 'patch', 'post'], '/{id}',                [VendorController::class, 'update']);
    Route::post('/{id}/share',                                              [VendorController::class, 'share']);
});
