<?php

namespace App\Filament\Admin\Resources\UserVendor\UserResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserResource;
use App\Filament\Admin\Resources\UserVendor\UserResource\Widgets\UserStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserStatsOverview::class,
        ];
    }
}
