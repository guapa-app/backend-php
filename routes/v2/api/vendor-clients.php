<?php

use App\Http\Controllers\VendorClientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->as('vendor.')->group(function () {
    Route::resource('vendor-clients', VendorClientController::class)
        ->only(['store', 'update', 'destroy'])
        ->withoutMiddleware('auth:api');
});
