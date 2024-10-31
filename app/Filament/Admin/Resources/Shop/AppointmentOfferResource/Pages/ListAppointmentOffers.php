<?php

namespace App\Filament\Admin\Resources\Shop\AppointmentOfferResource\Pages;

use App\Filament\Admin\Resources\Shop\AppointmentOfferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppointmentOffers extends ListRecords
{
    protected static string $resource = AppointmentOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
