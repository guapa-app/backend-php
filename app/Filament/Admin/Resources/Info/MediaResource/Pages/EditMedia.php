<?php

namespace App\Filament\Admin\Resources\Info\MediaResource\Pages;

use App\Filament\Admin\Resources\Info\MediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
