<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use DB;

class OrdersAmountChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Amount';
    protected static ?int $sort = 3;

    public function getFilters(): ?array
    {
        $currentYear = Carbon::now()->year;
        $years = [];

        // Generate years from 2025 to current year
        for ($year = 2025; $year <= $currentYear; $year++) {
            $years[(string) $year] = (string) $year;
        }

        return $years;
    }

    public function mount(): void
    {
        $this->filter = (string) Carbon::now()->year;
    }

    protected function getData(): array
    {
        $userCouponsIds = auth()->user()->coupons()->pluck('id')->toArray();

        $selectedYear = $this->filter ?? Carbon::now()->year;

        // Query to get orders discounted amounts per month for the current year
        $ordersCountPerMonth = Order::select(
            DB::raw('SUM(total) as total_amount'),
            DB::raw('MONTH(created_at) as month')
        )
            ->whereIn('coupon_id', $userCouponsIds)
            ->where('status', 'Accepted')
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize the data array with zeros for each month
        $monthlyCounts = array_fill(0, 12, 0);

        // Fill the monthly counts based on the query result
        foreach ($ordersCountPerMonth as $order) {
            $monthlyCounts[$order->month - 1] = $order->total_amount; // Adjust index (0-11 for Jan-Dec)
        }

        return [
            'datasets' => [
                [
                    'label' => 'amount',
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
