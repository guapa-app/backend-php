<?php

namespace App\Filament\Admin\Resources\Bkam\DiseaseResource\Pages;

use App\Filament\Admin\Resources\Bkam\DiseaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDisease extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = DiseaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
