<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SupportMessageStatus: string implements HasLabel
{
    case Draft = 'Draft';
    case Pending = 'Pending';
    case InProgress = 'In Progress';
    case Resolved = 'Resolved';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft         => 'Draft',
            self::Pending       => 'Pending',
            self::InProgress    => 'In Progress',
            self::Resolved      => 'Resolved',
        };
    }
}
