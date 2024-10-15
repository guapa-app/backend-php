<?php

namespace App\Filament\Admin\Resources\Shop\CouponTaxonomyResource\Pages;

use App\Filament\Admin\Resources\Shop\CouponTaxonomyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCouponTaxonomies extends ListRecords
{
    protected static string $resource = CouponTaxonomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
