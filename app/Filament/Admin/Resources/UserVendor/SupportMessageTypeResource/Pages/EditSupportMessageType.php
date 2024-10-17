<?php

namespace App\Filament\Admin\Resources\UserVendor\SupportMessageTypeResource\Pages;

use App\Filament\Admin\Resources\UserVendor\SupportMessageTypeResource;
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
