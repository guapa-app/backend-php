<?php

namespace App\Filament\Resources\Shop\ProductResource\Pages;

use App\Filament\Resources\Shop\ProductResource;
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
                ->url(route('filament.user.resources.shop.products.create') . '?type=product'),
            Actions\CreateAction::make()->label('New Service')
                ->url(route('filament.user.resources.shop.products.create') . '?type=service'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Product' => Tab::make()->query(fn ($query) => $query->product()),
            'Coupon' => Tab::make()->query(fn ($query) => $query->service()),
        ];
    }
}
