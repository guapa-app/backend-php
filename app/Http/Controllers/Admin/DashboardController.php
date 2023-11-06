<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends BaseAdminController
{
    public function main_stats(Request $request)
    {
        return response()->json([
            'id' => 0,
            'totalUsers' => \App\User::count(),
            'totalUsersToday' => \App\User::whereDate('created_at', Carbon::today())->count(),
            'totalAds' => \App\Ad::count(),
            'totalAdsToday' => \App\Ad::whereDate('created_at', Carbon::today())->count(),
            'totalRatings' => \App\Rating::count(),
            'totalRatingsToday' => \App\Rating::whereDate('created_at', Carbon::today())->count(),
        ]);
    }
}
