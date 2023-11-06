<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasLabel
{
    case Pending = 'Pending';
    case Accepted = 'Accepted';
    case Rejected = 'Rejected';
    case Cancel_Request = 'Cancel Request';
    case Canceled = 'Canceled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending        => 'Pending',
            self::Accepted       => 'Accepted',
            self::Rejected       => 'Rejected',
            self::Cancel_Request => 'Cancel Request',
            self::Canceled       => 'Canceled'
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending        => 'gray',
            self::Accepted       => 'success',
            self::Rejected       => 'warning',
            self::Cancel_Request => 'black',
            self::Canceled       => 'danger'
        };
    }

    public static function availableForUpdate(): array
    {
        return [
                self::Accepted,
                self::Rejected,
                self::Cancel_Request,
                self::Canceled,
        ];
    }
}
