<?php

namespace App\Filament\Admin\Resources\Shop\CouponUsageResource\Pages;

use App\Filament\Admin\Resources\Shop\CouponUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCouponUsage extends EditRecord
{
    protected static string $resource = CouponUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
