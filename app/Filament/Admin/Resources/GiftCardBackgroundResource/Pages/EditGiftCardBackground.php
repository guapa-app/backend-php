<?php

namespace App\Filament\Admin\Resources\GiftCardBackgroundResource\Pages;

use App\Filament\Admin\Resources\GiftCardBackgroundResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGiftCardBackground extends EditRecord
{
    protected static string $resource = GiftCardBackgroundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
