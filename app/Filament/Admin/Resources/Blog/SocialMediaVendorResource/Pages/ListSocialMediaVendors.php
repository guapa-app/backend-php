<?php

namespace App\Filament\Admin\Resources\Blog\SocialMediaVendorResource\Pages;

use App\Filament\Admin\Resources\Blog\SocialMediaVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialMediaVendors extends ListRecords
{
    protected static string $resource = SocialMediaVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
