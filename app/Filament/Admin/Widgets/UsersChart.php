<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Customers per month';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $currentYear = Carbon::now()->year;

        // Query to get orders count per month for the current year
        $ordersCountPerMonth = User::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month')
        )
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
                    'label' => 'Users',
                    'data' => $monthlyCounts,
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }
}
