<?php

namespace App\Filament\User\Resources\Info;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\UserVendor;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\FilamentVendorAccess;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\User\Resources\Info\StaffResource\Pages;

class UserVendorResource extends Resource
{
    use FilamentVendorAccess;

    protected static ?string $model = UserVendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Staff';

    protected static ?string $navigationGroup = 'Info';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('vendor_id')
                    ->default(self::authVendorId()),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique('users', 'email')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->unique('users', 'phone')
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->hiddenOn('edit'),
                    ]),
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        'manager' => 'Manager',
                        'staff' => 'Staff',
                        'doctor' => 'Doctor',
                        'admin' => 'Admin',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
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
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListUserVendors::route('/'),
            'create' => Pages\CreateUserVendor::route('/create'),
            'edit' => Pages\EditUserVendor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('vendor_id', self::authVendorId());
    }
}