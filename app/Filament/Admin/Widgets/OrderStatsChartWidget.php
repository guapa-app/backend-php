<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderStatsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Orders per month';

    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '250px';

    // Add year selector filter
    public function getFilters(): ?array
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        
        // Generate years from 2024 to current year
        for ($year = 2024; $year <= $currentYear; $year++) {
            $years[(string) $year] = (string) $year;
        }

        return $years;
    }

    // Set default filter value
    public function mount(): void
    {
        $this->filter = (string) Carbon::now()->year;
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Handle filter changes
    public function updatedFilter(string $filter): void
    {
        // The filter property is automatically managed by Filament
        // No need to manually set selectedYear
    }

    protected function getData(): array
    {
        // Use the filter value (which is managed by Filament) or default to current year
        $selectedYear = $this->filter ?? Carbon::now()->year;

        // Fetch data grouped by status and month
        $query = Order::select(
            DB::raw('COUNT(*) as count'),
            DB::raw('MONTH(created_at) as month'),
            'status'
        )
            ->whereYear('created_at', $selectedYear);

        $ordersData = $query
            ->groupBy('status', 'month')
            ->orderBy('month')
            ->get();

        // Get all possible statuses
        $statuses = Order::select('status')
            ->distinct()
            ->get()
            ->map(fn($order) => $order->status->value)
            ->toArray();

        // Initialize monthly counts for all statuses
        $monthlyCounts = [];
        foreach ($statuses as $status) {
            $monthlyCounts[$status] = array_fill(1, 12, 0);
        }

        // Populate counts for each status and month
        foreach ($ordersData as $order) {
            $monthlyCounts[$order->status->value][$order->month] = $order->count;
        }

        // Color mapping based on OrderStatus enum
        $colorMap = [
            'gray' => '#6B7280',
            'success' => '#10B981',
            'primary' => '#3B82F6',
            'warning' => '#F59E0B',
            'info' => '#60A5FA',
            'black' => '#1F2937',
            'danger' => '#EF4444',
            'Shipping' => '#8B5CF6',
        ];

        // Build datasets for the chart
        $datasets = [];
        foreach ($monthlyCounts as $statusValue => $counts) {
            $status = OrderStatus::from($statusValue);
            $label = $status->getLabel();
            $color = $colorMap[$status->getColor()] ?? '#6B7280';

            $datasets[] = [
                'label' => $label,
                'data' => array_values($counts),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'borderWidth' => 1,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }
}
