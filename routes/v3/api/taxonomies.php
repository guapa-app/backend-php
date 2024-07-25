<?php

use App\Http\Controllers\Api\V3\TaxonomyController;
use Illuminate\Support\Facades\Route;

Route::get('/',                                [TaxonomyController::class, 'index']);
Route::get('/{id}',                            [TaxonomyController::class, 'single']);
