<?php

namespace App\Filament\Admin\Resources\GiftCardBackgroundResource\Pages;

use App\Filament\Admin\Resources\GiftCardBackgroundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGiftCardBackgrounds extends ListRecords
{
    protected static string $resource = GiftCardBackgroundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
