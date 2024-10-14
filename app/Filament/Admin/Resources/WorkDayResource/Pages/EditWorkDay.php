<?php

namespace App\Filament\Admin\Resources\WorkDayResource\Pages;

use App\Filament\Admin\Resources\WorkDayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkDay extends EditRecord
{
    protected static string $resource = WorkDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
