<?php

namespace App\Filament\Admin\Resources\SupportMessageTypeResource\Pages;

use App\Filament\Admin\Resources\SupportMessageTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportMessageType extends EditRecord
{
    protected static string $resource = SupportMessageTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
