<?php

namespace App\Filament\User\Resources\Info\AppointmentResource\Pages;

use App\Filament\User\Resources\Info\AppointmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = auth()->user()->userVendors->first()->vendor_id;

        return $data;
    }
}
