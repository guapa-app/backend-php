<?php

namespace App\Enums;

enum TransactionOperation: string
{
    case DEPOSIT = 'Deposit';
    case WITHDRAWAL = 'Withdrawal';
}
