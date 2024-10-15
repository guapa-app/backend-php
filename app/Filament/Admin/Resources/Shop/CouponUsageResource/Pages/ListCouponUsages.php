<?php

namespace App\Filament\Admin\Resources\Shop\CouponUsageResource\Pages;

use App\Filament\Admin\Resources\Shop\CouponUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCouponUsages extends ListRecords
{
    protected static string $resource = CouponUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
