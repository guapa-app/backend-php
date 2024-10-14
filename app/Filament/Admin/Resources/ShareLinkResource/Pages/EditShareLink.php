<?php

namespace App\Filament\Admin\Resources\ShareLinkResource\Pages;

use App\Filament\Admin\Resources\ShareLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShareLink extends EditRecord
{
    protected static string $resource = ShareLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
