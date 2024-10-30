<?php

namespace App\Filament\Admin\Resources\AdminSetting\RoleResource\Pages;

use App\Filament\Admin\Resources\AdminSetting\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
