<?php

namespace App\Filament\Admin\Resources\AdminSetting\SettingResource\Pages;

use App\Filament\Admin\Resources\AdminSetting\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
