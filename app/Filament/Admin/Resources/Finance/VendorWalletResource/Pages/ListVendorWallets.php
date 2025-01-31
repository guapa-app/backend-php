<?php

namespace App\Filament\Admin\Resources\Finance\VendorWalletResource\Pages;

use App\Filament\Admin\Resources\Finance\VendorWalletResource;
use App\Filament\Admin\Resources\UserVendor\VendorResource\Widgets\TotalActiveWalletsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendorWallets extends ListRecords
{
    protected static string $resource = VendorWalletResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalActiveWalletsWidget::make(),
        ];
    }
}
