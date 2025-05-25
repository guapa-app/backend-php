<?php

use App\Http\Controllers\Api\V3\MarketingCampaignController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'campaigns.'], function () {
    Route::get('/', [MarketingCampaignController::class, 'index']);
    Route::post('/', [MarketingCampaignController::class, 'store']);

    // get campaign getCampaignAvailableCustomers count
    Route::get('/available-customers', [MarketingCampaignController::class, 'availableCustomers']);
    Route::post('/calculate-pricing', [MarketingCampaignController::class, 'calculatePricing']);
//
});

// change status of campaign and invoice
Route::post('/change-invoice-status', [MarketingCampaignController::class, 'changeStatus']);
