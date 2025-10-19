<?php

namespace App\Enums;

enum LoyaltyPointAction: string
{
    case PURCHASE = 'purchase';
    case RETURN_PURCHASE = 'return_purchase';
    case REFERRAL = 'referral';
    case CONVERSION = 'conversion';
    case WALLET_CHARGING = 'wallet_charging';
    case SPIN_WHEEL = 'spin_wheel';
    case FRIENDS_REGISTRATIONS = 'friends_registrations';
    case SYSTEM_ADDITION = 'system_addition';
    case SYSTEM_DEDUCTION = 'system_deduction';
}
