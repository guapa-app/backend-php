<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorClientResource\Pages;

use App\Filament\Admin\Resources\UserVendor\VendorClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendorClients extends ListRecords
{
    protected static string $resource = VendorClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
