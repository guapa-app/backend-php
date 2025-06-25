<?php

namespace App\Filament\Admin\Resources\GiftCardResource\Pages;

use App\Filament\Admin\Resources\GiftCardResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\GiftCard;

class EditGiftCard extends EditRecord
{
    protected static string $resource = GiftCardResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['code'])) {
            $data['code'] = strtoupper(uniqid('GC'));
        }
        // Ensure type, product_id, offer_id, vendor_id are always saved from the form
        // No restrictions on updating these fields
        return $data;
    }
}
