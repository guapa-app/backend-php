<?php

namespace App\Filament\Admin\Resources\Info\WalletChargingPackageResource\Pages;

use App\Filament\Admin\Resources\Info\WalletChargingPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditWalletChargingPackage extends EditRecord
{
    use Translatable;
    protected static string $resource = WalletChargingPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
