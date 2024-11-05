<?php

namespace App\Filament\Admin\Resources\Info\MarketingCampaignResource\Pages;

use App\Filament\Admin\Resources\Info\MarketingCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketingCampaigns extends ListRecords
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
