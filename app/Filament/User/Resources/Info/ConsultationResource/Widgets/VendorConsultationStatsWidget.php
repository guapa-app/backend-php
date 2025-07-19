<?php

namespace App\Filament\User\Resources\Info\ConsultationResource\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Consultation;
use Flowframe\Trend\TrendValue;
use App\Traits\FilamentVendorAccess;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class VendorConsultationStatsWidget extends BaseWidget
{
    use FilamentVendorAccess;

    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        // Get counts for each status
        $pendingCount = $this->getConsultationCountByStatus(Consultation::STATUS_PENDING);
        $scheduledCount = $this->getConsultationCountByStatus(Consultation::STATUS_SCHEDULED);
        $confirmedCount = $this->getConsultationCountByStatus(Consultation::STATUS_CONFIRMED);
        $completedCount = $this->getConsultationCountByStatus(Consultation::STATUS_COMPLETED);

        // Get trends for each status
        $pendingTrend = $this->getConsultationTrendByStatus(Consultation::STATUS_PENDING);
        $scheduledTrend = $this->getConsultationTrendByStatus(Consultation::STATUS_SCHEDULED);
        $confirmedTrend = $this->getConsultationTrendByStatus(Consultation::STATUS_CONFIRMED);
        $completedTrend = $this->getConsultationTrendByStatus(Consultation::STATUS_COMPLETED);

        return [
            Card::make('Pending Consultations', $pendingCount)
                ->description($this->getAverageDescription($pendingTrend))
                ->descriptionIcon('heroicon-s-clock')
                ->chart($pendingTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('warning'),

            Card::make('Scheduled Consultations', $scheduledCount)
                ->description($this->getAverageDescription($scheduledTrend))
                ->descriptionIcon('heroicon-s-calendar')
                ->chart($scheduledTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),

            Card::make('Confirmed Consultations', $confirmedCount)
                ->description($this->getAverageDescription($confirmedTrend))
                ->descriptionIcon('heroicon-s-check-circle')
                ->chart($confirmedTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),

            Card::make('Completed Consultations', $completedCount)
                ->description($this->getAverageDescription($completedTrend))
                ->descriptionIcon('heroicon-s-check-badge')
                ->chart($completedTrend->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),
        ];
    }

    protected function getConsultationCountByStatus(string $status): int
    {
        $vendorId = self::authVendorId();

        return Consultation::query()
            ->whereHas('user', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->where('status', $status)
            ->count();
    }

    protected function getConsultationTrendByStatus(string $status)
    {
        $vendorId = self::authVendorId();

        return Trend::query(Consultation::query()
            ->whereHas('user', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->where('status', $status))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
    }

    protected function getAverageDescription($trend): string
    {
        $average = $trend->average(fn(TrendValue $value) => $value->aggregate);
        return number_format($average, 1) . ' per month';
    }
}