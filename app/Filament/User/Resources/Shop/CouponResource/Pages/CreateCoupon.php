<?php

namespace App\Filament\User\Resources\Shop\CouponResource\Pages;

use App\Filament\User\Resources\Shop\CouponResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    protected static string $resource = CouponResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = auth()->user()->userVendors->first()->vendor_id;

        return $data;
    }
}
