<?php

namespace App\Filament\Admin\Resources\Shop\CouponProductResource\Pages;

use App\Filament\Admin\Resources\Shop\CouponProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCouponProduct extends EditRecord
{
    protected static string $resource = CouponProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
