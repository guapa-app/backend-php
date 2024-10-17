<?php

namespace App\Filament\Admin\Resources\Blog\SocialMediaVendorResource\Pages;

use App\Filament\Admin\Resources\Blog\SocialMediaVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialMediaVendor extends EditRecord
{
    protected static string $resource = SocialMediaVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
