<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class StaffRelationManager extends RelationManager
{
    protected static string $relationship = 'staff';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('pivot.role'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\AttachAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ]);
    }
}
