<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SupportMessageStatus: string implements HasLabel
{
    /*
     * make sure to keep reply status on the first line.
     * check to select
     */
    case Reply = 'Reply';
    case Draft = 'Draft';
    case Pending = 'Pending';
    case InProgress = 'In Progress';
    case Resolved = 'Resolved';

    public function getLabel(): string
    {
        return match ($this) {
            self::Reply         => 'Reply',
            self::Draft         => 'Draft',
            self::Pending       => 'Pending',
            self::InProgress    => 'In Progress',
            self::Resolved      => 'Resolved',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Reply, self::Resolved => 'success',
            self::Draft         => 'black',
            self::Pending       => 'gray',
            self::InProgress    => 'warning',
        };
    }

    public static function toSelect(): array
    {
        return array_column(array_slice(self::cases(), 1), 'name', 'value');
    }
}
