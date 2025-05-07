<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait FilamentVendorAccess
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->CurrentVendor(self::authVendorId());
    }

    public static function authVendorId()
    {
        return Auth::user()?->vendor?->id;
    }
}
