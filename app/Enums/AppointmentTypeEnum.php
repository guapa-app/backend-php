<?php

namespace App\Enums;

use App\Enums\Traits\EnumValuesTrait;

enum AppointmentTypeEnum: string
{
    use EnumValuesTrait;

    case SmallText = 'small_text';
    case LargeText = 'large_text';
    case SingleCheck = 'single_check';
    case DoubleCheck = 'double_check';
}
