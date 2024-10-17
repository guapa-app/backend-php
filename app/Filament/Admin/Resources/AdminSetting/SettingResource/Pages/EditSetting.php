<?php

namespace App\Filament\Admin\Resources\AdminSetting\SettingResource\Pages;

use App\Filament\Admin\Resources\AdminSetting\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
