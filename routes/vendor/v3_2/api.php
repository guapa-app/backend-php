<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->prefix("vendor/v3.2") ->group(function () {
    Route::prefix('orders')->group(base_path('routes/vendor/v3_2/api/orders.php'));
});
