<?php

namespace App\Filament\Admin\Resources\Info\CityResource\Pages;

use App\Filament\Admin\Resources\Info\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
