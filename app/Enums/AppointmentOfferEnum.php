<?php

namespace App\Enums;

use App\Enums\Traits\EnumValuesTrait;

enum AppointmentOfferEnum: string
{
    use EnumValuesTrait;

    case Pending = 'pending';
    case Paid_Application_Fees = 'paid_application_fees';
    case Paid_Appointment_Fees = 'paid_appointment_fees';
    case Accept = 'accept';
    case Reject = 'reject';
    case Canceled = 'canceled';
    case Refund = 'refund';

    public static function getValues(): array
    {
        return [
            self::Pending,
            self::Paid_Application_Fees,
            self::Paid_Appointment_Fees,
            self::Accept,
            self::Reject,
            self::Canceled,
            self::Refund,
        ];
    }


}
