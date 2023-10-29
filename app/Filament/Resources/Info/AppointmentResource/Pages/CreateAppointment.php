<?php

namespace App\Filament\Resources\Info\AppointmentResource\Pages;

use App\Filament\Resources\Info\AppointmentResource;
use Filament\Actions;
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
