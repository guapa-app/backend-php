<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Vendor;

class ContractWidget extends Widget
{
    protected static string $view = 'filament.widgets.contract-widget';

    public function getContractUrl()
    {
        // Assuming you want to get the contract for the first vendor
        // You might want to adjust this logic based on your specific requirements
        $vendor = auth()->user()->userVendors->first()->vendor;
//        dd($vendor);
        if ($vendor && $vendor->contract) {
            return $vendor->contract->url;
        }

        return null;
    }

}
