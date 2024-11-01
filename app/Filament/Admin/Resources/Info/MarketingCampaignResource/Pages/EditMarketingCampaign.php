<?php

namespace App\Filament\Admin\Resources\Info\MarketingCampaignResource\Pages;

use App\Filament\Admin\Resources\Info\MarketingCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingCampaign extends EditRecord
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
