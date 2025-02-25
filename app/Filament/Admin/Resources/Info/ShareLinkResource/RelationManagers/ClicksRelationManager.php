<?php

namespace App\Filament\Admin\Resources\Info\ShareLinkResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'clicks';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('platform')
                    ->badge()
                    ->colors([
                        'primary' => 'web',
                        'success' => 'ios',
                        'warning' => 'android',
                    ]),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limitList(2)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Clicked At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('platform')
                    ->options([
                        'web' => 'Web',
                        'ios' => 'iOS',
                        'android' => 'Android',
                    ]),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
