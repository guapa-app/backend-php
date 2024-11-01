<?php

namespace App\Filament\Admin\Resources\AdminSetting;

use App\Filament\Admin\Resources\AdminSetting\AdminUserPointHistoryResource\Pages;
use App\Models\AdminUserPointHistory;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AdminUserPointHistoryResource extends Resource
{
    protected static ?string $model = AdminUserPointHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Admin Setting';

    protected static ?string $label = 'Point History';

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
                Components\TextEntry::make('admin.name'),
                Components\TextEntry::make('user.name'),
                Components\TextEntry::make('points'),
                Components\TextEntry::make('action'),
                Components\TextEntry::make('reason')
                    ->columnSpanFull(),
                Components\TextEntry::make('created_at')
                    ->dateTime('M j, Y'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('admin.name')
                    ->limit(30)
                    ->url(fn ($record) => route(
                        'filament.admin.resources.admin-setting.admins.edit',
                        ['record' => $record->admin_id]
                    ))
                    ->openUrlInNewTab(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->limit(30)
                    ->url(fn ($record) => route(
                        'filament.admin.resources.user-vendor.users.edit',
                        ['record' => $record->user_id]
                    ))
                    ->openUrlInNewTab(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('points'),
                Tables\Columns\TextColumn::make('action')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(30),
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
            'index' => Pages\ListAdminUserPointHistories::route('/'),
            'create' => Pages\CreateAdminUserPointHistory::route('/create'),
            'edit' => Pages\EditAdminUserPointHistory::route('/{record}/edit'),
        ];
    }
}
