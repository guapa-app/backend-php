<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasLabel
{
    case Product = 'product';
    case Service = 'service';

    public function getLabel(): string
    {
        return match ($this) {
            self::Product        => 'Product',
            self::Service         => 'Service',
        };
    }
}
