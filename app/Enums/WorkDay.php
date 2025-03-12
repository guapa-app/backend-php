<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorkDay: int implements HasLabel
{
    case allDays = 0;
    case Sunday = 1;
    case Monday = 2;
    case Tuesday = 3;
    case Wednesday = 4;
    case Thursday = 5;
    case Friday = 6;
    case Saturday = 7;

    public function getLabel(): string
    {
        return match ($this) {
            self::allDays  => 'All Days',
            self::Sunday    => 'Sunday',
            self::Monday    => 'Monday',
            self::Tuesday   => 'Tuesday',
            self::Wednesday => 'Wednesday',
            self::Thursday  => 'Thursday',
            self::Friday    => 'Friday',
            self::Saturday  => 'Saturday'
        };
    }

    public static function toSelect(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
