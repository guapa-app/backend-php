<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\Coupon;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Enums\OrderStatus;

class CouponsDetailsTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                query: Coupon::query()
                    ->whereHas('users', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->withCount([
                        'orders as accepted_orders_count' => function ($query) {
                            $query->where('status', OrderStatus::Accepted);
                        }
                    ])
                    ->withSum([
                        'orders as total_discounted_amount' => function ($query) {
                            $query->where('status', OrderStatus::Accepted);
                        }
                    ], 'discount_amount')
                    ->withSum([
                        'orders as total_amount' => function ($query) {
                            $query->where('status', OrderStatus::Accepted);
                        }
                    ], 'total')

            )
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
            ->paginated([5,10,15])
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_uses')
                    ->sortable(),

                Tables\Columns\TextColumn::make('single_user_usage')
                    ->sortable(),

                Tables\Columns\TextColumn::make('accepted_orders_count')
                    ->label('Accepted Orders Count')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_discounted_amount')
                    ->label('Total Discounted Amount')
                    ->sortable(),
            ]);
    }
}
