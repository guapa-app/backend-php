<?php

namespace App\Enums;

use App\Enums\Traits\EnumValuesTrait;

enum OrderTypeEnum: string
{
    use EnumValuesTrait;

    case Product = 'product';
    case Service = 'service';
    case Appointment = 'appointment';
}
