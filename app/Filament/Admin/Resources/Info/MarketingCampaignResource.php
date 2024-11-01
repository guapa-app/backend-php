<?php

namespace App\Filament\Admin\Resources\Info;

use App\Filament\Admin\Resources\Info\MarketingCampaignResource\Pages;
use App\Models\MarketingCampaign;
use App\Models\Offer;
use App\Models\Product;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MarketingCampaignResource extends Resource
{
    protected static ?string $model = MarketingCampaign::class;

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
                Components\TextEntry::make('vendor.name'),
                Components\TextEntry::make('channel'),
                Components\TextEntry::make('audience_type'),
                Components\TextEntry::make('audience_count'),
                Components\TextEntry::make('message_cost')->money('SAR'),
                Components\TextEntry::make('taxes')->money('SAR'),
                Components\TextEntry::make('total_cost')->money('SAR'),
                Components\TextEntry::make('invoice_url')
                    ->url(fn ($record) => $record->invoice_url)
                    ->openUrlInNewTab(),
                Components\TextEntry::make('status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->limit(30)
                    ->url(fn ($record) => route(
                        'filament.admin.resources.user-vendor.vendors.edit',
                        ['record' => $record->vendor_id]
                    ))
                    ->openUrlInNewTab(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('campaignable.title')
                    ->label('Title')
                    ->url(function ($record) {
                        if ($record instanceof Product) {
                            return route('filament.admin.resources.shop.products.edit', ['record' => $record]);
                        }
                        if ($record instanceof Offer) {
                            return route('filament.admin.resources.shop.offers.edit', ['record' => $record]);
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('Type')
                    ->state(function (Model $record) {
                        if ($record->campaignable instanceof Product) {
                            return $record->campaignable->type ?? 'N/A';
                        } else {
                            return class_basename($record->campaignable_type) ?? 'N/A';
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('channel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('audience_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('audience_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
            'index' => Pages\ListMarketingCampaigns::route('/'),
            'create' => Pages\CreateMarketingCampaign::route('/create'),
            'edit' => Pages\EditMarketingCampaign::route('/{record}/edit'),
        ];
    }
}
