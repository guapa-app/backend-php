<?php

namespace App\Filament\Admin\Resources\Info\TaxonomyResource\Pages;

use App\Filament\Admin\Resources\Info\TaxonomyResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
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

    public function getTabs(): array
    {
        return [
            'Procedures' => Tab::make()->query(fn ($query) => $query->type('specialty')),
            'Products' => Tab::make()->query(fn ($query) => $query->type('category')),
            'Blogs' => Tab::make()->query(fn ($query) => $query->type('blog_category')),
            'Special' => Tab::make()->query(fn ($query) => $query->type('special')),
        ];
    }
}
