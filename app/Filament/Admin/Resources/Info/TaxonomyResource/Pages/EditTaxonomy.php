<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Pages;

use App\Filament\Admin\Resources\Info\TaxonomyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaxonomy extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = TaxonomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
