<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\MarketingCampaignController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/', [MarketingCampaignController::class, 'index'])->name('v3.campaigns.index');
    Route::post('/', [MarketingCampaignController::class, 'store'])->name('v3.campaigns.store');
    Route::get('/{id}', [MarketingCampaignController::class, 'show'])->name('v3.campaigns.show');
    Route::match(['put', 'patch', 'post'], '/{id}', [MarketingCampaignController::class, 'update'])->name('v3.campaigns.update');
    Route::delete('/{id}', [MarketingCampaignController::class, 'destroy'])->name('v3.campaigns.destroy');

    // get campaign getCampaignAvailableCustomers count
    Route::get('/available-customers', [MarketingCampaignController::class, 'availableCustomers'])->name('v3.campaigns.available_customers');
    Route::post('/calculate-pricing', [MarketingCampaignController::class, 'calculatePricing'])->name('v3.campaigns.calculate_pricing');
});

// change status of campaign and invoice
Route::post('/change-invoice-status', [MarketingCampaignController::class, 'changeStatus'])->name('v3.campaigns.changeStatus');
