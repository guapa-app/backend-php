<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Pages;

use App\Filament\Admin\Resources\Info\TaxonomyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTaxonomy extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = TaxonomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
