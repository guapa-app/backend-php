<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use DB;

class OrdersDiscountedAmountChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Discounted Amount';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $userCouponsIds = auth()->user()->coupons()->pluck('id')->toArray();

        $currentYear = Carbon::now()->year;

        // Query to get orders discounted amounts per month for the current year
        $ordersCountPerMonth = Order::select(
            DB::raw('SUM(discount_amount) as total_discounted_amount'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereIn('coupon_id', $userCouponsIds)
            ->where('status', 'Accepted')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize the data array with zeros for each month
        $monthlyCounts = array_fill(0, 12, 0);

        // Fill the monthly counts based on the query result
        foreach ($ordersCountPerMonth as $order) {
            $monthlyCounts[$order->month - 1] = $order->total_discounted_amount; // Adjust index (0-11 for Jan-Dec)
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
        return 'line';
    }
}
