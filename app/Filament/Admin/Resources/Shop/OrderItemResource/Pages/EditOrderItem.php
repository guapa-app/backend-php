<?php

namespace App\Filament\Admin\Resources\Shop\OrderItemResource\Pages;

use App\Filament\Admin\Resources\Shop\OrderItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderItem extends EditRecord
{
    protected static string $resource = OrderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
