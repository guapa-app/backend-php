<?php

namespace App\Filament\Admin\Resources\UserVendor\SupportMessageTypeResource\Pages;

use App\Filament\Admin\Resources\UserVendor\SupportMessageTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportMessageTypes extends ListRecords
{
    protected static string $resource = SupportMessageTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
