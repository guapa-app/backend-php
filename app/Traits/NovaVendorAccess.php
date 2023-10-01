<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Http\Requests\NovaRequest;

trait NovaVendorAccess
{
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (Auth::user()?->isVendor()) {
            return $query->CurrentVendor(Auth::user()->vendor->id);
        } else {
            return $query;
        }
    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        if (Auth::user()?->isVendor() && $request->resource() !== "App\Nova\Resources\Vendor") {
            $query->CurrentVendor(Auth::user()->vendor->id);
        }

        return parent::detailQuery($request, $query);
    }
}
