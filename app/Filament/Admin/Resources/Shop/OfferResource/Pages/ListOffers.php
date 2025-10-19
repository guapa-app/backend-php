<?php

namespace App\Filament\Admin\Resources\Shop\OfferResource\Pages;

use App\Filament\Admin\Resources\Shop\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOffers extends ListRecords
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
