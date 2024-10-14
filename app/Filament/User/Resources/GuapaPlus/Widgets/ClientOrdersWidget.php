<?php

namespace App\Filament\User\Resources\GuapaPlus\Widgets;

use App\Models\VendorClient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientOrdersWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $vendor = auth()->user()->userVendors->first()->vendor;
        $clients  = VendorClient::where('vendor_id', $vendor->id);
        $clientIds = $clients->pluck('user_id');
        $clientCount = $clients->count();

        $orderCount = \App\Models\Order::where('vendor_id', $vendor->id)->whereIn('user_id', $clientIds)->count();

        return [
            Stat::make('Total Clients', $clientCount)
                ->description('Number of clients')
                ->descriptionIcon('heroicon-s-users'),

            Stat::make('Clients Orders', $orderCount)
                ->description('Number of my clients orders')
                ->descriptionIcon('heroicon-s-shopping-cart'),

        ];
    }
}
