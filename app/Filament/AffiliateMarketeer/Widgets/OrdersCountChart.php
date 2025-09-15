<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use DB;

class OrdersCountChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Count';
    protected static ?int $sort = 3;

    // public function getFilters(): ?array
    // {
    //     $currentYear = Carbon::now()->year;
    //     $years = [];

    //     // Generate years from 2025 to current year
    //     for ($year = 2025; $year <= $currentYear; $year++) {
    //         $years[(string) $year] = (string) $year;
    //     }

    //     return $years;
    // }

    protected function getData(): array
    {
        $userCouponsIds = auth()->user()->coupons()->pluck('id')->toArray();

        $currentYear = Carbon::now()->year;

        // Query to get orders count per month for the current year
        $ordersCountPerMonth = Order::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereIn('coupon_id', $userCouponsIds)
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize the data array with zeros for each month
        $monthlyCounts = array_fill(0, 12, 0);

        // Fill the monthly counts based on the query result
        foreach ($ordersCountPerMonth as $order) {
            $monthlyCounts[$order->month - 1] = $order->count; // Adjust index (0-11 for Jan-Dec)
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $monthlyCounts,
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
