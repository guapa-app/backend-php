<?php

namespace App\Filament\Admin\Resources\Appointment\AppointmentFormResource\Pages;

use App\Filament\Admin\Resources\Appointment\AppointmentFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointmentForm extends EditRecord
{
    protected static string $resource = AppointmentFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
