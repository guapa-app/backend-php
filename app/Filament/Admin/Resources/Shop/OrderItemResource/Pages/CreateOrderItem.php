<?php

namespace App\Filament\Admin\Resources\Shop\OrderItemResource\Pages;

use App\Filament\Admin\Resources\Shop\OrderItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderItem extends CreateRecord
{
    protected static string $resource = OrderItemResource::class;
}
