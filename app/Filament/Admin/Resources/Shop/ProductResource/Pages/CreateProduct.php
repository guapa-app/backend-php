<?php

namespace App\Filament\Admin\Resources\Shop\ProductResource\Pages;

use App\Filament\Admin\Resources\Shop\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
