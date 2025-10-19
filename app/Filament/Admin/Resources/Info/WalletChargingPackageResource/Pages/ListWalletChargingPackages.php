<?php

namespace App\Filament\Admin\Resources\Info\WalletChargingPackageResource\Pages;

use App\Filament\Admin\Resources\Info\WalletChargingPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

class ListWalletChargingPackages extends ListRecords
{
    use Translatable;
    protected static string $resource = WalletChargingPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
