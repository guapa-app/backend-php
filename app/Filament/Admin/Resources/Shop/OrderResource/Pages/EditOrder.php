<?php

namespace App\Filament\Admin\Resources\Shop\OrderResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\Shop\OrderResource;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;
}