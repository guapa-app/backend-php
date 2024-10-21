<?php

namespace App\Filament\Admin\Resources\Shop\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'Order Items';

    protected static ?string $recordTitleAttribute = 'title';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('offer.title'),
                Tables\Columns\TextColumn::make('offer.discount')->label('Discount %'),
            ]);
    }
}
