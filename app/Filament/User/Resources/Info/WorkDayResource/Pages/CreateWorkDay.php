<?php

namespace App\Filament\User\Resources\Info\WorkDayResource\Pages;

use App\Filament\User\Resources\Info\WorkDayResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkDay extends CreateRecord
{
    protected static string $resource = WorkDayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = auth()->user()->userVendors->first()->vendor_id;

        return $data;
    }
}
