<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Filament\Widgets\ChartWidget;

class TotalPointsChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Points Count';
    protected static ?int $sort = 3;

    public ?int $userId = null;

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
        $user = $this->userId ? User::find($this->userId) : auth()->user();
        $selectedYear = $this->filter ?? Carbon::now()->year;

        // Query to get points count per month for the current year
        $pointsCountPerMonth = $user->loyaltyPointHistory()
            ->select(
                DB::raw('SUM(points) as points_count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize the data array with zeros for each month
        $monthlyCounts = array_fill(0, 12, 0);

        // Fill the monthly counts based on the query result
        foreach ($pointsCountPerMonth as $points) {
            $monthlyCounts[$points->month - 1] = $points->points_count; // Adjust index (0-11 for Jan-Dec)
        }

        return [
            'datasets' => [
                [
                    'label' => 'Points',
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
