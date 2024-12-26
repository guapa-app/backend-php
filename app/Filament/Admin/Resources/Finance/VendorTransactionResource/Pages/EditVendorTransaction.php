<?php

namespace App\Filament\Admin\Resources\Finance\VendorTransactionResource\Pages;

use App\Filament\Admin\Resources\Finance\VendorTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendorTransaction extends EditRecord
{
    protected static string $resource = VendorTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
