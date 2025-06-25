<?php

namespace App\Filament\Admin\Resources\GiftCardBackgroundResource\Pages;

use App\Filament\Admin\Resources\GiftCardBackgroundResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGiftCardBackground extends CreateRecord
{
    protected static string $resource = GiftCardBackgroundResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();
        return $data;
    }
}
