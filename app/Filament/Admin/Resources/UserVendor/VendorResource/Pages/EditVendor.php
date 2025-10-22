<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;

use App\Filament\Admin\Resources\UserVendor\VendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendor extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
