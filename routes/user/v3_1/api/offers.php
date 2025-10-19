<?php

use App\Http\Controllers\Api\User\V3_1\OfferController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OfferController::class, 'index']);
