<?php

namespace App\Filament\Admin\Resources\Info;

use App\Models\WheelSpin;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WheelSpinResource extends Resource
{
    protected static ?string $model = WheelSpin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Info';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canReplicate(Model $record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\TextEntry::make('user.name'),
                Components\TextEntry::make('wheel.rarity_title'),
                Components\TextEntry::make('points_awarded'),
                Components\TextEntry::make('spin_date')->date('M j, Y'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wheel.rarity_title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points_awarded')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('spin_date')
                    ->dateTime('M j, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => WheelSpinResource\Pages\ListWheelSpins::route('/'),
            'create' => WheelSpinResource\Pages\CreateWheelSpin::route('/create'),
            'edit' => WheelSpinResource\Pages\EditWheelSpin::route('/{record}/edit'),
        ];
    }
}
