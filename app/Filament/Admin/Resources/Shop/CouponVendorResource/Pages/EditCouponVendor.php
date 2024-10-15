<?php

namespace App\Filament\Admin\Resources\Shop\CouponVendorResource\Pages;

use App\Filament\Admin\Resources\Shop\CouponVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCouponVendor extends EditRecord
{
    protected static string $resource = CouponVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
