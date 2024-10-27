<?php

namespace App\Filament\Admin\Resources\Info\WorkDayResource\Pages;

use App\Filament\Admin\Resources\Info\WorkDayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkDays extends ListRecords
{
    protected static string $resource = WorkDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
