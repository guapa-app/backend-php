<?php

namespace App\Filament\Admin\Resources\Finance\AffiliateMarketeerResource\Widgets;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Widgets\TableWidget as BaseWidget;

class OrdersDetailsTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public ?int $userId = null;
    public function table(Table $table): Table
    {
        $user = $this->userId ? User::find($this->userId) : auth()->user();
        $userCouponsIds = $user->coupons()->pluck('id')->toArray();
        return $table
            ->query(
                Order::whereIn('coupon_id', $userCouponsIds)
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
            ->paginated([5, 10, 15])
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discount_amount')
                    ->label('Discount Amount')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->date()
                    ->sortable(),
            ]);
    }
}
