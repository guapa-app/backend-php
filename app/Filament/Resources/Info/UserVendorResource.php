<?php

namespace App\Filament\Resources\Info;

use App\Filament\Resources\Info\StaffResource\Pages;
use App\Models\UserVendor;
use App\Traits\FilamentVendorAccess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserVendorResource extends Resource
{
    use FilamentVendorAccess;
    protected static ?string $model = UserVendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Staff';

    protected static ?string $navigationGroup = 'Info';

    public static function getCurrentUserVendorId()
    {
        return Auth::user()->userVendors->first()->vendor_id;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('user.phone')->unique('users', 'phone')
                    ->label('Phone')
                    ->required(),
                Forms\Components\TextInput::make('user.email')->unique('users', 'email')
                    ->label('Email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('user.password')
                    ->label('Password')
                    ->password()
                    ->required(),
                Forms\Components\Select::make('user.role')
                    ->label('Role')
                    ->options([
                        'manager' => 'Manager',
//                        'doctor' => 'Doctor',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('user.phone'),
                Tables\Columns\TextColumn::make('role'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
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
            'index' => Pages\ListUserVendors::route('/'),
            'create' => Pages\CreateUserVendor::route('/create'),
//            'edit' => Pages\EditUserVendor::route('/{record}/edit'),
        ];
    }
}
