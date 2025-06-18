<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorConsultationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\UserVendor\VendorConsultationResource;

class EditVendorConsultation extends EditRecord
{
    protected static string $resource = VendorConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}