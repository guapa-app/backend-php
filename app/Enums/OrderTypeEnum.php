<?php

namespace App\Enums;

use App\Enums\Traits\EnumValuesTrait;

enum OrderTypeEnum: string
{
    use EnumValuesTrait;

    case Order = 'order';
    case Appointment = 'appointment';
}
