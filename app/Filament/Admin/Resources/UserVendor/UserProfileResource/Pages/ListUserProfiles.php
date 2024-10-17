<?php

namespace App\Filament\Admin\Resources\UserVendor\UserProfileResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserProfiles extends ListRecords
{
    protected static string $resource = UserProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
