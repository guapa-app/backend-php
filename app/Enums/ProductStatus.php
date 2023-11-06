<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductStatus: string implements HasLabel
{
    case Draft = 'Draft';
    case Published = 'Published';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft          => 'Draft',
            self::Published      => 'Published',
        };
    }
}
