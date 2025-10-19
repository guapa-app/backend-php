<?php

namespace App\Filament\Admin\Resources\AdminSetting;

use App\Filament\Admin\Resources\AdminSetting\DatabaseNotificationResource\Pages;
use App\Models\DatabaseNotification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DatabaseNotificationResource extends Resource
{
    protected static ?string $model = DatabaseNotification::class;

    protected static ?string $navigationIcon = 'heroicon-s-bolt';

    protected static ?string $navigationGroup = 'Admin Setting';

    protected static ?string $label = 'Notifications';

    public static function table(Table $table): Table
    {
        return $table
            ->query(parent::getEloquentQuery()
                ->selectRaw('MIN(id) as id, data, MAX(created_at) as created_at, MAX(updated_at) as updated_at')
                ->where('type', 'App\Notifications\PushNotification')
                ->groupBy('data'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data.title')
                    ->label('title'),
                Tables\Columns\TextColumn::make('data.summary')
                    ->label('summary')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated([10])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListDatabaseNotifications::route('/'),
        ];
    }
}
