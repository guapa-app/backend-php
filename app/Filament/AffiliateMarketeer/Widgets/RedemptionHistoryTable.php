<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\LoyaltyPointHistory;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Widgets\TableWidget as BaseWidget;

class RedemptionHistoryTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public ?int $userId = null;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                query: LoyaltyPointHistory::query()
                    ->where('user_id', $this->userId ?? auth()->id())
                    ->where('action', \App\Enums\LoyaltyPointAction::CONVERSION->value)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Forms\Components\Select::make('year')
                            ->options(
                                collect(range(now()->year, 2025))
                                    ->mapWithKeys(fn($year) => [$year => $year])
                            )
                            ->label('Year'),

                        Forms\Components\Select::make('month')
                            ->options([
                                1 => 'January',
                                2 => 'February',
                                3 => 'March',
                                4 => 'April',
                                5 => 'May',
                                6 => 'June',
                                7 => 'July',
                                8 => 'August',
                                9 => 'September',
                                10 => 'October',
                                11 => 'November',
                                12 => 'December',
                            ])
                            ->label('Month'),
                    ])
                    ->query(function ($query, array $data) {
                        if (!$data['year'] || !$data['month']) {
                            return;
                        }

                        $start = Carbon::create($data['year'], $data['month'], 1)->startOfMonth();
                        $end = Carbon::create($data['year'], $data['month'], 1)->endOfMonth();

                        $query->whereBetween('created_at', [$start, $end]);
                    }),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 15])
            ->defaultSort('created_at', 'desc')
            ;
    }
}
