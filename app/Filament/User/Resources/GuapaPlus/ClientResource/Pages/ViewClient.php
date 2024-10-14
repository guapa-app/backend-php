<?php

namespace App\Filament\User\Resources\GuapaPlus\ClientResource\Pages;

use App\Filament\User\Resources\GuapaPlus\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
