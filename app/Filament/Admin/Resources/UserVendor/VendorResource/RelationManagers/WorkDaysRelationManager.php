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
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day'),
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
