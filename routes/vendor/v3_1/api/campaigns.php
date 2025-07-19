<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\V3_1\MarketingCampaignController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/', [MarketingCampaignController::class, 'index'])->name('v3_1.vendor.campaigns.index');
    Route::post('/', [MarketingCampaignController::class, 'store'])->name('v3_1.vendor.campaigns.store');
    Route::get('/{id}', [MarketingCampaignController::class, 'show'])->name('v3_1.vendor.campaigns.show');
    Route::match(['put', 'patch', 'post'], '/{id}', [MarketingCampaignController::class, 'update'])->name('v3_1.vendor.campaigns.update');
    Route::delete('/{id}', [MarketingCampaignController::class, 'destroy'])->name('v3_1.vendor.campaigns.destroy');

    // get campaign getCampaignAvailableCustomers count
    Route::get('/available-customers', [MarketingCampaignController::class, 'availableCustomers'])->name('v3_1.vendor.campaigns.available_customers');
    Route::post('/calculate-pricing', [MarketingCampaignController::class, 'calculatePricing'])->name('v3_1.vendor.campaigns.calculate_pricing');
});

// change status of campaign and invoice
Route::post('/change-invoice-status', [MarketingCampaignController::class, 'changeStatus'])->name('v3_1.vendor.campaigns.changeStatus');

