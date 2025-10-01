<?php

namespace App\Filament\Admin\Resources\UserVendor;

use App\Filament\Admin\Resources\UserVendor\VendorResource\Widgets\TotalActiveWalletsWidget;
use Filament\Forms;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Taxonomy;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Admin\Resources\UserVendor\VendorResource\Pages;
use App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;

class VendorResource extends Resource
{
    use Translatable;

    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User & Vendor';

    public static function canCreate(): bool
    {
        return true;
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
                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->required()
                    ->options(Country::query()->pluck('name', 'id'))
                    ->searchable(),
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

                // Add the specialties field
                Forms\Components\Select::make('specialties')
                    ->label('Specialties')
                    ->multiple()
                    ->relationship('specialties', 'title')
                    ->options(function () {
                        return Taxonomy::where('type', 'specialty')->pluck('title', 'id');
                    })
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),

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
                Forms\Components\Select::make('accept_online_consultation' )
                    ->options([
                        0 => 'disabled',
                        1 => 'active',
                    ])
                    ->native(false)
                    ->default(0)
                    ->required(),

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
                Tables\Columns\TextColumn::make('wallet.balance')
                    ->label('Wallet Balance')
                    ->money()
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('activate_wallet')
                    ->label('Wallet Active')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        0 => 'disabled',
                        1 => 'active',
                    ]),
                Tables\Columns\TextColumn::make('specialties.title')
                    ->badge()
                    ->label('Specialties')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('verified_badge')
                    ->options([
                        0 => 'not verified',
                        1 => 'verified',
                    ]),
                Tables\Columns\TextColumn::make('favorites_count')
                    ->label('Favorited By')
                    ->getStateUsing(function (Vendor $record): string {
                        return $record->favoritedBy()->count() . ' users';
                    }),
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
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\ServicesRelationManager::class,
            RelationManagers\OrdersRelationManager::class,
            RelationManagers\FavoritesRelationManager::class,
            RelationManagers\TransactionsRelationManager::class,
            RelationManagers\ConsultationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
            'view' => Pages\ViewVendor::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            TotalActiveWalletsWidget::class,
        ];
    }
}