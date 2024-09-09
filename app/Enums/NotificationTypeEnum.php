<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case Order = 'new-order';
    case Offer = 'new-offer';
    case Message = 'message';
}
