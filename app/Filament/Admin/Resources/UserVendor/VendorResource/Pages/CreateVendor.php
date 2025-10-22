<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;

use App\Filament\Admin\Resources\UserVendor\VendorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVendor extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
