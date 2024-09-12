<?php

namespace App\Filament\Resources\Info\VendorResource\Pages;

use App\Filament\Resources\Info\VendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendor extends EditRecord
{
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        abort_if(auth()->user()->Vendor->isChild(), 403);
    }
}
