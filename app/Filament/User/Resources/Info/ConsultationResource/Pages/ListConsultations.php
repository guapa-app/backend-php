<?php

namespace App\Filament\User\Resources\Info\ConsultationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\User\Resources\Info\ConsultationResource;
use App\Filament\User\Resources\Info\ConsultationResource\Widgets\VendorConsultationStatsWidget;

class ListConsultations extends ListRecords
{
    protected static string $resource = ConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VendorConsultationStatsWidget::class,
        ];
    }
}
