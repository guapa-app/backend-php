<?php

namespace App\Filament\Admin\Resources\Bkam\DiseaseResource\Pages;

use App\Filament\Admin\Resources\Bkam\DiseaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDiseases extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = DiseaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
