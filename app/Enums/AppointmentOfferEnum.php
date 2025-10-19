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
    case Refunded = 'refunded';
    case Completed = 'completed';
}
