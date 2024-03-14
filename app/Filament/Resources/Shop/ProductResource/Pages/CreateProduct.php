<?php

namespace App\Filament\Resources\Shop\ProductResource\Pages;

use App\Enums\ProductReview;
use App\Enums\ProductStatus;
use App\Filament\Resources\Shop\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['review'] = ProductReview::Approved;
        $data['status'] = ProductStatus::Published;
        $data['vendor_id'] = auth()->user()->userVendors->first()->vendor_id;

        return $data;
    }
}
