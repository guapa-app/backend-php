<?php

namespace App\Filament\Admin\Resources\Appointment\AppointmentFormResource\Pages;

use App\Filament\Admin\Resources\Appointment\AppointmentFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppointmentForms extends ListRecords
{
    protected static string $resource = AppointmentFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
