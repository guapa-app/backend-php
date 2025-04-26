<?php

namespace App\Filament\Admin\Resources\UserVendor\ConsultationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\UserVendor\ConsultationResource;

class EditConsultations extends EditRecord
{
    protected static string $resource = ConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
