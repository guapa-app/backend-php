<?php

namespace App\Filament\Admin\Resources\Info\WheelOfFortuneResource\Pages;

use App\Filament\Admin\Resources\Info\WheelOfFortuneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;
use Filament\Resources\Pages\ListRecords;

class ListWheelOfFortunes extends ListRecords
{
    use Translatable;
    protected static string $resource = WheelOfFortuneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
