<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserResource\Widgets\UserOrderStats;
use App\Filament\Admin\Resources\UserVendor\VendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVendor  extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;
    public static string $resource = VendorResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            UserOrderStats::make(['record' => $this->record]),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
