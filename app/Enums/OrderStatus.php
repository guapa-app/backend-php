<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasLabel
{
    case Pending = 'Pending';
    case Accepted = 'Accepted';
    case Rejected = 'Rejected';
    case Expired = 'Expired';
    case Used = 'Used';
    case Prepare_For_Delivery = 'Prepare for delivery';
    case Shipping = 'Shipping';
    case Deliveried = 'Delivered';
    case Return_Request = 'Return Request';
    case Returned = 'Returned';
    case Cancel_Request = 'Cancel Request';
    case Canceled = 'Canceled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending              => 'Pending',
            self::Accepted             => 'Accepted',
            self::Rejected             => 'Rejected',
            self::Expired              => 'Expired',
            self::Used                 => 'Used',
            self::Prepare_For_Delivery => 'Prepare for delivery',
            self::Shipping             => 'Shipping',
            self::Deliveried           => 'Delivered',
            self::Return_Request       => 'Return Request',
            self::Returned             => 'Returned',
            self::Cancel_Request       => 'Cancel Request',
            self::Canceled             => 'Canceled',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending              => 'gray',
            self::Accepted             => 'success',
            self::Rejected             => 'primary',
            self::Expired              => 'warning',
            self::Used                 => 'info',
            self::Prepare_For_Delivery => 'gray',
            self::Shipping             => 'Shipping',
            self::Deliveried           => 'success',
            self::Return_Request       => 'black',
            self::Returned             => 'warning',
            self::Cancel_Request       => 'black',
            self::Canceled             => 'danger',
        };
    }

    public static function availableForUpdate(): array
    {
        return array_column([
            self::Accepted,
            self::Rejected,
            self::Expired,
            self::Used,
            self::Prepare_For_Delivery,
            self::Shipping,
            self::Deliveried,
            self::Return_Request,
            self::Returned,
            self::Cancel_Request,
            self::Canceled,
        ], 'value', 'name');
    }

    public static function availableForUpdateByVendor(): array
    {
        return array_column([
            self::Accepted,
            self::Rejected,
            self::Expired,
            self::Used,
            self::Prepare_For_Delivery,
            self::Shipping,
            self::Deliveried,
            self::Returned,
            self::Canceled,
        ], 'value', 'name');
    }

    public static function notAvailableForCancle(): array
    {
        return array_column([
            self::Rejected,
            self::Expired,
            self::Used,
            self::Deliveried,
            self::Return_Request,
            self::Returned,
            self::Canceled,
        ], 'value', 'name');
    }

    public static function notAvailableForExpire(): array
    {
        return array_column([
            self::Rejected,
            self::Expired,
            self::Used,
            self::Deliveried,
            self::Return_Request,
            self::Returned,
            self::Cancel_Request,
            self::Canceled,
        ], 'value', 'name');
    }

    public static function notAvailableShowInvoice(): array
    {
        return array_column([
            self::Pending,
            self::Rejected,
        ], 'value', 'name');
    }
}
