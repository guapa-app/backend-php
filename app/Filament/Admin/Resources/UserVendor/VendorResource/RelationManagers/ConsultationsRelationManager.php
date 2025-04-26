<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use App\Enums\TransactionType;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
use Filament\Tables\Table;

class ConsultationsRelationManager extends RelationManager
{
    protected static string $relationship = 'consultations';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('consultation_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('operation')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Deposit' => 'success',
                        'Withdrawal' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('operation')
                    ->options(TransactionType::class),
            ]);
    }
}
