<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\Widgets;

use App\Models\Vendor;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class TotalActiveWalletsWidget extends BaseWidget
{
    protected function getColumns(): int
    {
        return 2;
    }
    protected function getCards(): array
    {
        $totalActiveWallets = Vendor::where('activate_wallet', 1)->count();
        $totalBalance = Wallet::whereNotNull('vendor_id')->sum('balance');

        return [
            Card::make('Total Active Wallets', $totalActiveWallets)
                ->description('Number of active vendor wallets')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Card::make('Total Wallet Balance', number_format($totalBalance, 2))
                ->description('Total balance of active vendor wallets')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
