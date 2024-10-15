<?php

namespace App\Filament\Admin\Resources\AdminSetting\AdminResource\Pages;

use App\Filament\Admin\Resources\AdminSetting\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
