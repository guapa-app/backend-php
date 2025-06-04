<?php

namespace App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class StaffRelationManager extends RelationManager
{
    protected static string $relationship = 'staff';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'manager' => 'success',
                        'doctor' => 'warning',
                        'staff' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'manager' => 'Manager',
                        'staff' => 'Staff',
                        'doctor' => 'Doctor',
                    ])
                    ->attribute('pivot.role'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\Select::make('role')
                            ->options([
                                'manager' => 'Manager',
                                'staff' => 'Staff',
                                'doctor' => 'Doctor',
                            ])
                            ->required(),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->tel(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\Select::make('role')
                            ->options([
                                'manager' => 'Manager',
                                'staff' => 'Staff',
                                'doctor' => 'Doctor',
                            ])
                            ->required(),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
