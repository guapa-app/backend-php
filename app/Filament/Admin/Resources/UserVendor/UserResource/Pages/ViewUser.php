<?php

namespace App\Filament\Admin\Resources\UserVendor\UserResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserResource;
use App\Filament\Admin\Resources\UserVendor\UserResource\RelationManagers\OrdersRelationManager;
use App\Filament\Admin\Resources\UserVendor\UserResource\Widgets\UserOrderStats;
use Filament\Resources\Pages\ViewRecord;
class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            UserOrderStats::make(
                [
                    'record' => $this->record,
                ]
            ),
        ];
    }
}
