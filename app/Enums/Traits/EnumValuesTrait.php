<?php

declare(strict_types=1);

namespace App\Enums\Traits;

trait EnumValuesTrait
{
    /**
     * @return array<string>
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
