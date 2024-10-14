<?php

namespace App\Filament\Admin\Resources\CouponTaxonomyResource\Pages;

use App\Filament\Admin\Resources\CouponTaxonomyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCouponTaxonomy extends EditRecord
{
    protected static string $resource = CouponTaxonomyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
