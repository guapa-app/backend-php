<?php

namespace App\Filament\Admin\Resources\Shop\InvoiceResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Order Items';

    public static function canViewForRecord($record, $pageClass): bool
    {
        return $record->invoiceable_type === \App\Models\Order::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->label('Item ID'),
                Tables\Columns\TextColumn::make('product.title')
                    ->searchable()
                    ->label('Product'),
                Tables\Columns\TextColumn::make('product.type')
                    ->badge()
                    ->label('Product Type'),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->label('Quantity'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('SAR')
                    ->sortable()
                    ->label('Unit Price'),
                Tables\Columns\TextColumn::make('amount_to_pay')
                    ->money('SAR')
                    ->sortable()
                    ->label('Amount to Pay'),
                Tables\Columns\TextColumn::make('taxes')
                    ->numeric()
                    ->sortable()
                    ->label('Taxes %'),
                Tables\Columns\TextColumn::make('offer.title')
                    ->label('Applied Offer'),
                Tables\Columns\TextColumn::make('offer.discount')
                    ->label('Discount %'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned Staff'),
                Tables\Columns\TextColumn::make('appointment')
                    ->label('Appointment'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product.type')
                    ->options([
                        'product' => 'Product',
                        'service' => 'Service',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
