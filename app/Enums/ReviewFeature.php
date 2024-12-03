<?php

namespace App\Enums;

enum ReviewFeature: string
{
    case QUALITY = 'quality';
    case STAFF = 'staff';
    case PRICE = 'price';
    case DELIVERY = 'delivery';
    case SERVICE = 'service';

}
