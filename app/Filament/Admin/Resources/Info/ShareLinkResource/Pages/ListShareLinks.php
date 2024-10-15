<?php

namespace App\Filament\Admin\Resources\Info\ShareLinkResource\Pages;

use App\Filament\Admin\Resources\Info\ShareLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShareLinks extends ListRecords
{
    protected static string $resource = ShareLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
