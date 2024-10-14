<?php

namespace App\Filament\Admin\Resources\UserVendorResource\Pages;

use App\Filament\Admin\Resources\UserVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserVendor extends EditRecord
{
    protected static string $resource = UserVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
