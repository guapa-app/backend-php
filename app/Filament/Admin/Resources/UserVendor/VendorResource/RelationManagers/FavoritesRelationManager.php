<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class FavoritesRelationManager  extends RelationManager
{
    protected static string $relationship = 'favoritedBy';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
            ])
            ->actions([
            ]);
    }
}
