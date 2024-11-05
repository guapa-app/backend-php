<?php

namespace App\Filament\Admin\Resources\AdminSetting\AdminUserPointHistoryResource\Pages;

use App\Filament\Admin\Resources\AdminSetting\AdminUserPointHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminUserPointHistory extends EditRecord
{
    protected static string $resource = AdminUserPointHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
