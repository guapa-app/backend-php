<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Pages;

use App\Filament\Admin\Resources\Info\TaxonomyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaxonomies extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = TaxonomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
