<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;

use App\Filament\Admin\Resources\UserVendor\VendorResource;
use App\Filament\Admin\Resources\UserVendor\VendorResource\Widgets\TotalActiveWalletsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendors extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalActiveWalletsWidget::make(),
        ];
    }
}
