<?php

namespace App\Filament\Admin\Resources\GiftCardSettingResource\Pages;

use App\Filament\Admin\Resources\GiftCardSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGiftCardSetting extends CreateRecord
{
    protected static string $resource = GiftCardSettingResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return GiftCardSettingResource::mutateFormDataBeforeSave($data);
    }
}
