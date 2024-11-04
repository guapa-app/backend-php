<?php

namespace App\Filament\Admin\Resources\Appointment\AppointmentOfferResource\Pages;

use App\Filament\Admin\Resources\Appointment\AppointmentOfferResource;
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
