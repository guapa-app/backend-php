<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FilamentVendorAccess
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->CurrentVendor(Auth::user()->userVendors->first()->vendor_id);
    }
}
