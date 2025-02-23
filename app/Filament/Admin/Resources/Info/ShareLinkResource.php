<?php

namespace App\Filament\Admin\Resources\Info;

use App\Filament\Admin\Resources\Info\ShareLinkResource\Pages;
use App\Filament\Admin\Resources\Info\ShareLinkResource\RelationManagers;
use App\Models\ShareLink;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class ShareLinkResource extends Resource
{
    protected static ?string $model = ShareLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Info';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('clicks')
            ->with('shareable');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('shareable_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('shareable_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('identifier')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('identifier')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('link')
                    ->searchable()
                    ->copyable()
                    ->wrap(),
                BadgeColumn::make('shareable_type')
                    ->colors([
                        'primary' => 'vendor',
                        'success' => 'product',
                    ]),
                TextColumn::make('shareable.name')
                    ->label('Shared Item')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        if ($record->shareable_type === 'vendor') {
                            return $record->shareable?->name ?? 'N/A';
                        }
                        return $record->shareable?->title ?? 'N/A';
                    }),
                TextColumn::make('clicks_count')
                    ->counts('clicks')
                    ->label('Total Clicks')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('shareable_type')
                    ->options([
                        'vendor' => 'Vendor',
                        'product' => 'Product',
                    ]),
                Tables\Filters\Filter::make('high_performing')
                    ->query(fn (Builder $query) => $query->has('clicks', '>', 10))
                    ->label('High Performing Links'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-chart-bar'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Link Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('identifier')
                            ->label('Short Code')
                            ->copyable()
                            ->weight(FontWeight::Bold),
                        Infolists\Components\TextEntry::make('link')
                            ->label('Full URL')
                            ->copyable()
                            ->url(fn ($record) => $record->link)
                            ->openUrlInNewTab(),
                        Infolists\Components\TextEntry::make('shareable_type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'vendor' => 'primary',
                                'product' => 'success',
                                default => 'gray',
                            }),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Analytics')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('clicks_count')
                                    ->label('Total Clicks')
                                    ->numeric()
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('unique_visitors')
                                    ->label('Unique Visitors')
                                    ->state(fn ($record) => $record->clicks()->distinct('ip_address')->count())
                                    ->numeric()
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('last_clicked')
                                    ->label('Last Click')
                                    ->state(fn ($record) => $record->clicks()->latest()->first()?->created_at)
                                    ->date()
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                            ]),
                    ]),

                Infolists\Components\Section::make('Platform Distribution')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('web_clicks')
                                    ->label('Web Clicks')
                                    ->state(fn ($record) => $record->clicks()->where('platform', 'web')->count())
                                    ->numeric(),

                                Infolists\Components\TextEntry::make('ios_clicks')
                                    ->label('iOS Clicks')
                                    ->state(fn ($record) => $record->clicks()->where('platform', 'ios')->count())
                                    ->numeric(),

                                Infolists\Components\TextEntry::make('android_clicks')
                                    ->label('Android Clicks')
                                    ->state(fn ($record) => $record->clicks()->where('platform', 'android')->count())
                                    ->numeric(),
                            ]),
                    ]),
            ]);
    }


    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShareLinks::route('/'),
            'create' => Pages\CreateShareLink::route('/create'),
            'edit' => Pages\EditShareLink::route('/{record}/edit'),
//            'view' => Pages\ShareLinkAnalytics::route('/{record}'),
        ];
    }
}
