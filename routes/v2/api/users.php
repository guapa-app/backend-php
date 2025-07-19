<?php

use App\Http\Controllers\Api\V2\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                             [UserController::class,  'index'])->name('v2.users.index');
Route::get('/{id}',                                         [UserController::class,  'show'])->name('v2.users.show');
Route::match(['put', 'patch', 'post'], '/{id}', 	[UserController::class,  'update'])->name('v2.users.update');
Route::delete('/{id}',                                      [UserController::class,  'delete'])->name('v2.users.delete');
