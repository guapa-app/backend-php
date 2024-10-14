<?php

namespace App\Filament\User\Resources\Shop\OrderResource\Pages;

use App\Filament\User\Resources\Shop\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
