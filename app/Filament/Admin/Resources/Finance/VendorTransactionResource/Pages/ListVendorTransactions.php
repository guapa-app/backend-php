<?php

namespace App\Filament\Admin\Resources\Finance\VendorTransactionResource\Pages;

use App\Filament\Admin\Resources\Finance\VendorTransactionResource;
use App\Filament\Admin\Resources\Finance\VendorTransactionResource\Widgets\VendorTransactionStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendorTransactions extends ListRecords
{
    protected static string $resource = VendorTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            VendorTransactionStatsWidget::make(),
        ];
    }

}
