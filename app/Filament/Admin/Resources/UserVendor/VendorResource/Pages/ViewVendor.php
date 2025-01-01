<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserResource\Widgets\UserOrderStats;
use App\Filament\Admin\Resources\UserVendor\VendorResource;
use Filament\Resources\Pages\ViewRecord;

class ViewVendor  extends ViewRecord
{
    public static string $resource = VendorResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            UserOrderStats::make(['record' => $this->record]),
        ];
    }
}
