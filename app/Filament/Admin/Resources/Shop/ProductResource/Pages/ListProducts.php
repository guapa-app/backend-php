<?php

namespace App\Filament\Admin\Resources\Shop\ProductResource\Pages;

use App\Filament\Admin\Resources\Shop\ProductResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Product')
                ->url(route('filament.admin.resources.shop.products.create') . '?type=product'),
            Actions\CreateAction::make()->label('New Procedure')
                ->url(route('filament.admin.resources.shop.products.create') . '?type=service'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Products' => Tab::make()->query(fn ($query) => $query->product()),
            'Procedures' => Tab::make()->query(fn ($query) => $query->service()),
        ];
    }
}
