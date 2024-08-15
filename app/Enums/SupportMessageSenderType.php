<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SupportMessageSenderType: string implements HasLabel
{
    case Admin = 'Admin';
    case User = 'User';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin      => 'Admin',
            self::User       => 'User',
        };
    }

    public static function toSelect(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
