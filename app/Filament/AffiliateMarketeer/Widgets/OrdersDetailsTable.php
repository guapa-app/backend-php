<?php

namespace App\Filament\AffiliateMarketeer\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OrdersDetailsTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        $userCouponsIds = auth()->user()->coupons()->pluck('id')->toArray();
        return $table
            ->query(
                Order::whereIn('coupon_id', $userCouponsIds)
            )
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
