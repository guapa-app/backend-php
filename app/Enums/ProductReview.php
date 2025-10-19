<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductReview: string implements HasLabel
{
    case Approved = 'Approved';
    case Blocked = 'Blocked';
    case Pending = 'Pending';

    public function getLabel(): string
    {
        return match ($this) {
            self::Approved   => 'Approved',
            self::Blocked    => 'Blocked',
            self::Pending    => 'Pending',
        };
    }
}
