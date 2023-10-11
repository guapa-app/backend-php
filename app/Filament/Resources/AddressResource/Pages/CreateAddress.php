<?php

namespace App\Filament\Resources\AddressResource\Pages;

use App\Filament\Resources\AddressResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAddress extends CreateRecord
{
    protected static string $resource = AddressResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['addressable_type'] = 'vendor';
        $data['addressable_id'] = auth()->user()->userVendors->first()->vendor_id;

        return $data;
    }
}
