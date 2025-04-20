<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorkDay: int implements HasLabel
{
    case Sunday = 0;
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;
    case allDays = 7;

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
    public function getValue(): int
    {
        return match ($this) {
            self::allDays  => 7,
            self::Sunday    => 0,
            self::Monday    => 1,
            self::Tuesday   => 2,
            self::Wednesday => 3,
            self::Thursday  => 4,
            self::Friday    => 5,
            self::Saturday  => 6
        };
    }
    public static function toSelect(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
