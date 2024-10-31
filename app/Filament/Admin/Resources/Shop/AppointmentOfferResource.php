<?php

namespace App\Filament\Admin\Resources\Shop;

use App\Filament\Admin\Resources\Shop\AppointmentOfferResource\Pages;
use App\Filament\Admin\Resources\Shop\AppointmentOfferResource\RelationManagers;
use App\Models\AppointmentOffer;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppointmentOfferResource extends Resource
{
    protected static ?string $model = AppointmentOffer::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Shop';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('taxonomy.title')
                    ->label('Taxonomy')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'danger' => 'refund',
                        'success' => 'paid_appointment_fees',
                        'info' => 'paid_application_fees',
                    ]),
                Tables\Columns\TextColumn::make('notes')
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DetailsRelationManager::class,
            RelationManagers\InvoiceRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointmentOffers::route('/'),
            'view' => Pages\ViewAppointmentOffer::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
