<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorConsultationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\UserVendor\VendorConsultationResource;

class ViewVendor extends ViewRecord
{
    protected static string $resource = VendorConsultationResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}