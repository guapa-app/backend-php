<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\ReviewResource\Pages;
use App\Models\Review;
use App\Traits\FilamentVendorAccess;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;

class ReviewResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Shop';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name'),
                Infolists\Components\TextEntry::make('stars')->badge(),
                Infolists\Components\TextEntry::make('comment')
            ]);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('reviewable_type')->label(__('name'))
                ->state(fn ($record): string => get_class($record->reviewable) == 'App\Models\Vendor' ? $record->reviewable->name : $record->reviewable->title)
                ->searchable(),
            Tables\Columns\TextColumn::make('user_id')->label(__('user'))
                ->state(fn ($record): string => $record->user->name),
            Tables\Columns\TextColumn::make('stars')
                ->numeric()
                ->badge(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        return $table
            ->columns($columns)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
        ];
    }
}
