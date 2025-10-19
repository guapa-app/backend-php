<?php

namespace App\Filament\Admin\Resources\Info\WalletChargingPackageResource\Pages;

use App\Filament\Admin\Resources\Info\WalletChargingPackageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;


class CreateWalletChargingPackage extends CreateRecord
{
    use Translatable;
    protected static string $resource = WalletChargingPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
