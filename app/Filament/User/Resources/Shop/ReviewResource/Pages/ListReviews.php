<?php

namespace App\Filament\User\Resources\Shop\ReviewResource\Pages;

use App\Filament\User\Resources\Shop\ReviewResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Vendor' => Tab::make()->query(fn ($query) => $query->Vendor()),
            'Product' => Tab::make()->query(fn ($query) => $query->Product()),
        ];
    }
}
