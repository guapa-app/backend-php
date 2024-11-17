<?php

namespace App\Filament\Admin\Resources\Info\WheelOfFortuneResource\Pages;

use App\Filament\Admin\Resources\Info\WheelOfFortuneResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;


class CreateWheelOfFortune extends CreateRecord
{
    use Translatable;
    protected static string $resource = WheelOfFortuneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
