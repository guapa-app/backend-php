<?php

use App\Http\Controllers\Api\V3\MarketingCampaignController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api', 'as' => 'campaigns.'], function () {
    Route::get('/',                  [MarketingCampaignController::class, 'index'])->name('index');
    Route::post('/',                  [MarketingCampaignController::class, 'store'])->name('store');
});
