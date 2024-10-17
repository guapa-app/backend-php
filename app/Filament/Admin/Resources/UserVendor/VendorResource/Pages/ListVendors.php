<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;

use App\Filament\Admin\Resources\UserVendor\VendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendors extends ListRecords
{
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
