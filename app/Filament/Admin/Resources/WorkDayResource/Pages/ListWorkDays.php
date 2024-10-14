<?php

namespace App\Filament\Admin\Resources\WorkDayResource\Pages;

use App\Filament\Admin\Resources\WorkDayResource;
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
