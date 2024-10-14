<?php

namespace App\Filament\Admin\Resources\SupportMessageResource\Pages;

use App\Filament\Admin\Resources\SupportMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportMessage extends EditRecord
{
    protected static string $resource = SupportMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
