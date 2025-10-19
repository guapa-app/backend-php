<?php

namespace App\Filament\Admin\Resources\Shop\CouponResource\RelationManagers;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\Shop\OrderResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Orders';

    protected static ?string $recordTitleAttribute = 'id';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('total')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->searchable()
                    ->label('Vendor'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(OrderStatus::class),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn ($record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye')
            ])
            ->defaultSort('created_at', 'desc')
            ->heading('Orders Using This Coupon');
    }
}
