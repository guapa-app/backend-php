<?php

namespace App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Pages;

use App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliateMarketeer extends EditRecord
{
    protected static string $resource = AffiliateMarketeerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
