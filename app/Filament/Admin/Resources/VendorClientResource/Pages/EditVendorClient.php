<?php

namespace App\Filament\Admin\Resources\VendorClientResource\Pages;

use App\Filament\Admin\Resources\VendorClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendorClient extends EditRecord
{
    protected static string $resource = VendorClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
