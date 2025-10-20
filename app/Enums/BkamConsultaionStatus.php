<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BkamConsultaionStatus: string implements HasLabel
{
    /*
     * make sure to keep reply status on the first line.
     * check to select
     */
    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public static function toSelect(): array
    {
        return array_column(array_slice(self::cases(), 1), 'name', 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
