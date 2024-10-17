<?php

namespace App\Filament\Admin\Resources\Info\AddressResource\Pages;

use App\Filament\Admin\Resources\Info\AddressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAddress extends EditRecord
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
