<?php

namespace App\Filament\Admin\Resources\Shop\OfferResource\Pages;

use App\Filament\Admin\Resources\Shop\OfferResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateOffer extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
