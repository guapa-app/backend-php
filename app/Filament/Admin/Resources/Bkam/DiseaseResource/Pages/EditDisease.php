<?php

namespace App\Filament\Admin\Resources\Bkam\DiseaseResource\Pages;

use App\Filament\Admin\Resources\Bkam\DiseaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisease extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = DiseaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
