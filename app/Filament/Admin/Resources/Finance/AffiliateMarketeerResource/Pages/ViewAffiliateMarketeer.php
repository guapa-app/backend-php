<?php

namespace App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Pages;

use App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAffiliateMarketeer extends ViewRecord
{
    protected static string $resource = AffiliateMarketeerResource::class;

    public function getHeading(): string
    {
        return 'Affiliate: ' . $this->record->name;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\AffiliateMarketeer\Widgets\CouponsDetailsTable::make(['userId' => $this->record->id]),
            \App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Widgets\OrdersDetailsTable::make(['userId' => $this->record->id]),
            \App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Widgets\OrdersAmountChart::make(['userId' => $this->record->id]),
            \App\Filament\AffiliateMarketeer\Widgets\OrdersCountChart::make(['userId' => $this->record->id]),
            \App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Widgets\OrdersDiscountedAmountChart::make(['userId' => $this->record->id]),
        ];
    }
}
