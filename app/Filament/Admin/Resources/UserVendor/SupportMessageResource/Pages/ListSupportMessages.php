<?php

namespace App\Filament\Admin\Resources\UserVendor\SupportMessageResource\Pages;

use App\Filament\Admin\Resources\UserVendor\SupportMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportMessages extends ListRecords
{
    protected static string $resource = SupportMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
