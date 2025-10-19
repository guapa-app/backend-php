<?php

namespace App\Filament\Admin\Resources\Info\WheelOfFortuneResource\Pages;

use App\Filament\Admin\Resources\Info\WheelOfFortuneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditWheelOfFortune extends EditRecord
{
    use Translatable;

    protected static string $resource = WheelOfFortuneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
