<?php

namespace App\Filament\User\Resources\Shop\ProductResource\Pages;

use App\Filament\User\Resources\Shop\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
