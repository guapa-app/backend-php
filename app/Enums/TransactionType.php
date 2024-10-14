<?php

namespace App\Enums;

enum TransactionType: string
{
    case RECHARGE = 'recharge';
    case POINTS_TRANSFER = 'points_transfer';
    case DEBIT_FROM_WALLET = 'debit_from_wallet';
}
