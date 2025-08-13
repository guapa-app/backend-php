<?php

namespace App\Filament\Admin\Resources\AdminSetting\AdminEmailResource\Pages;

use App\Filament\Admin\Resources\AdminSetting\AdminEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminEmail extends EditRecord
{
    protected static string $resource = AdminEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
