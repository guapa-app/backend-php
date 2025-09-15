<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\Coupon;
use Filament\Tables;
use Filament\Tables\Table;
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
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5,10,15])
            ->defaultSort('created_at', 'desc')
            ->columns([
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

                Tables\Columns\TextColumn::make('total_discounted_amount')
                    ->label('Total Discounted Amount')
                    ->sortable(),
            ]);
    }
}
