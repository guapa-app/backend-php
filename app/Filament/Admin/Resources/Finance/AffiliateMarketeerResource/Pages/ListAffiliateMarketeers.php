<?php

namespace App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Pages;

use App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAffiliateMarketeers extends ListRecords
{
    protected static string $resource = AffiliateMarketeerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
