<?php

namespace App\Filament\Admin\Resources\UserVendor;

use App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;
use App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User & Vendor';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Media Files')
                    ->columns()
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logos')
                            ->placeholder('Upload the vendor logo'),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('contract')
                            ->collection('contracts')
                            ->rules('nullable')
                            ->placeholder('Upload the vendor contract (PDF, DOC, or DOCX file)'),
                    ])
                    ->collapsible(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(150),
                Forms\Components\TextInput::make('email')
                    ->rules([
                        'required',
                        'email',
                    ])
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('working_hours')
                    ->maxLength(255),

                Forms\Components\Section::make()
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                0 => 'disabled',
                                1 => 'active',
                            ])
                            ->native(false)
                            ->default(1)
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options(Vendor::TYPES)
                            ->native(false)
                            ->required(),
                        Forms\Components\Toggle::make('verified')
                            ->required(),
                    ]),

                Forms\Components\Textarea::make('about')
                    ->columnSpanFull(),

                Forms\Components\Fieldset::make('Social Media')->schema([
                    Forms\Components\TextInput::make('whatsapp')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('twitter')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('instagram')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('snapchat')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('website_url')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('known_url')
                        ->maxLength(255),
                ]),
                Forms\Components\Fieldset::make('Additional Information')->schema([
                    Forms\Components\TextInput::make('tax_number')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cat_number')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('reg_number')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('health_declaration')
                        ->maxLength(255),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        0 => 'disabled',
                        1 => 'active',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorkDaysRelationManager::class,
            RelationManagers\StaffRelationManager::class,
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
