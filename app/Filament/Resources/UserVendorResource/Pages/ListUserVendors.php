<?php

namespace App\Filament\Resources\UserVendorResource\Pages;

use App\Filament\Resources\UserVendorResource;
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
