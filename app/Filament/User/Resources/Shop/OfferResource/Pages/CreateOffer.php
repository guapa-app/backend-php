<?php

namespace App\Filament\User\Resources\Shop\OfferResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\User\Resources\Shop\OfferResource;
use App\Contracts\Repositories\OfferRepositoryInterface;

class CreateOffer extends CreateRecord
{
    protected static string $resource = OfferResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $repository = app(OfferRepositoryInterface::class);

        // Get the current vendor ID
        $vendorId = auth()->user()->userVendors->first()->vendor_id;

        // Add the vendor to the data array
        $data['vendors'] = [$vendorId];

        // Use the repository to create the coupon
        return $repository->create($data);
    }
}
