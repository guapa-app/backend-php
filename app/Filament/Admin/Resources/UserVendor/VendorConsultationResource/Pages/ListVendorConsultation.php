<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorConsultationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\UserVendor\VendorConsultationResource;
use App\Filament\Admin\Resources\UserVendor\VendorConsultationResource\Widgets\VendorConsultationStatsWidget;

class ListVendorConsultation extends ListRecords
{
    protected static string $resource = VendorConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}