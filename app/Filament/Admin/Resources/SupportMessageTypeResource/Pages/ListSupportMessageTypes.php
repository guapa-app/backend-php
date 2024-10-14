<?php

namespace App\Filament\Admin\Resources\SupportMessageTypeResource\Pages;

use App\Filament\Admin\Resources\SupportMessageTypeResource;
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
