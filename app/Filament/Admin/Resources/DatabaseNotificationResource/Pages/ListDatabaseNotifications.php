<?php

namespace App\Filament\Admin\Resources\DatabaseNotificationResource\Pages;

use App\Filament\Admin\Resources\DatabaseNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatabaseNotifications extends ListRecords
{
    protected static string $resource = DatabaseNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
