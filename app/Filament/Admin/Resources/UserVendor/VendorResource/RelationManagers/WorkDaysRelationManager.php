<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use App\Enums\WorkDay as EnumsWorkDay;
use App\Enums\WorkType;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;

class WorkDaysRelationManager extends RelationManager
{
    protected static string $relationship = 'workDays';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day')
                    ->required()
                    ->options(EnumsWorkDay::class),
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->label('Start Time'),
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->label('End Time'),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(['online' => 'Online', 'offline' => 'Offline'])
                    ->label('Type')
                    ->default('offline'),
                Forms\Components\Select::make(name: 'is_active')
                    ->required()
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day'),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start Time'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'offline' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}