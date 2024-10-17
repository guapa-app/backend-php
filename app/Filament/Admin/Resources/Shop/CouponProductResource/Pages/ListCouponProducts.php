<?php

namespace App\Filament\Admin\Resources\Shop\CouponProductResource\Pages;

use App\Filament\Admin\Resources\Shop\CouponProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCouponProducts extends ListRecords
{
    protected static string $resource = CouponProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
