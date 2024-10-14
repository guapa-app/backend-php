<?php

namespace App\Filament\Admin\Resources\CouponVendorResource\Pages;

use App\Filament\Admin\Resources\CouponVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCouponVendors extends ListRecords
{
    protected static string $resource = CouponVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
