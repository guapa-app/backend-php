<?php

namespace App\Filament\Admin\Resources\Info\WheelSpinResource\Pages;

use App\Filament\Admin\Resources\Info\WheelSpinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWheelSpin extends EditRecord
{
    protected static string $resource = WheelSpinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
