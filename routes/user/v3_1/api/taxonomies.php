<?php

use App\Http\Controllers\Api\User\V3_1\TaxonomyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaxonomyController::class, 'index']);
Route::get('/{id}', [TaxonomyController::class, 'single']);
