<?php

namespace App\Filament\Admin\Resources\UserVendor\UserProfileResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserProfile extends EditRecord
{
    protected static string $resource = UserProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
