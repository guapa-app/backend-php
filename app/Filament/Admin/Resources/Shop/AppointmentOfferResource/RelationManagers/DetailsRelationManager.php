<?php

namespace App\Filament\Admin\Resources\Shop\AppointmentOfferResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    protected static ?string $recordTitleAttribute = 'id';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('ID'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.name')
                    ->label(__('Vendor'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('offer_price')
                    ->label('Offer price'),
                Tables\Columns\TextColumn::make('reject_reason')
                    ->label(__('Reject reason')),
                Tables\Columns\TextColumn::make('staff_notes')
                    ->label(__('Staff Note')),
                Tables\Columns\TextColumn::make('offer_notes')
                    ->label(__('Offer Note')),
                Tables\Columns\TextColumn::make('terms')
                    ->label(__('Terms')),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label(__('Starts at'))
                    ->dateTime(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label(__('Expires at'))
                    ->dateTime(),
            ]);
    }
}
