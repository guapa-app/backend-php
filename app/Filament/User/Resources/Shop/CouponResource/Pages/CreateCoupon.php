<?php

namespace App\Filament\User\Resources\Shop\CouponResource\Pages;

use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Filament\User\Resources\Shop\CouponResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    protected static string $resource = CouponResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $repository = app(CouponRepositoryInterface::class);

        // Get the current vendor ID
        $vendorId = auth()->user()->userVendors->first()->vendor_id;

        // Add the vendor to the data array
        $data['vendors'] = [$vendorId];

        // Use the repository to create the coupon
        return $repository->create($data);
    }
}
