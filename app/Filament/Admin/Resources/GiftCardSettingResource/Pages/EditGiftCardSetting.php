<?php

namespace App\Filament\Admin\Resources\GiftCardSettingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\GiftCardSettingResource;

class EditGiftCardSetting extends EditRecord
{
    protected static string $resource = GiftCardSettingResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return GiftCardSettingResource::mutateFormDataBeforeFill($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return GiftCardSettingResource::mutateFormDataBeforeSave($data);
    }
}
