<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use App\Enums\WorkDay as EnumsWorkDay;
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