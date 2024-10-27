<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
