<?php

namespace App\Filament\Admin\Resources\Info\DeviceResource\Pages;

use App\Filament\Admin\Resources\Info\DeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDevice extends EditRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
