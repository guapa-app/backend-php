<?php

namespace App\Filament\Admin\Resources\AdminSetting\DatabaseNotificationResource\Pages;

use App\Filament\Admin\Resources\AdminSetting\DatabaseNotificationResource;
use App\Filament\Admin\Resources\AdminSetting\DatabaseNotificationResource\Actions\SendNotificationAction;
use Filament\Resources\Pages\ListRecords;

class ListDatabaseNotifications extends ListRecords
{
    protected static string $resource = DatabaseNotificationResource::class;

    public function getHeaderActions(): array
    {
        return [
            SendNotificationAction::make('send-notification'),
        ];
    }
}
