<?php

namespace App\Filament\Admin\Resources\Info\HistoryResource\Pages;

use App\Filament\Admin\Resources\Info\HistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHistories extends ListRecords
{
    protected static string $resource = HistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
