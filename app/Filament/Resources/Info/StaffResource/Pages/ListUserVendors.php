<?php

namespace App\Filament\Resources\Info\StaffResource\Pages;

use App\Filament\Resources\Info\UserVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserVendors extends ListRecords
{
    protected static string $resource = UserVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
