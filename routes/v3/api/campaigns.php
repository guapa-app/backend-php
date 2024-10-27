<?php

use App\Http\Controllers\Api\V3\MarketingCampaignController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api', 'as' => 'campaigns.'], function () {
    Route::get('/', [MarketingCampaignController::class, 'index'])->name('index');
    Route::post('/', [MarketingCampaignController::class, 'store'])->name('store');

    // get campaign getCampaignAvailableCustomers count
    Route::get('/available-customers', [MarketingCampaignController::class, 'availableCustomers'])->name('available_customers');
    Route::post('/calculate-pricing', [MarketingCampaignController::class, 'calculatePricing'])->name('calculate_pricing');
//
});

// change status of campaign and invoice
Route::post('/change-invoice-status', [MarketingCampaignController::class, 'changeStatus'])->name('changeStatus');
