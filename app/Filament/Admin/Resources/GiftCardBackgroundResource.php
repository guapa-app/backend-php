<?php

namespace App\Filament\Admin\Resources;

use App\Models\GiftCardBackground;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class GiftCardBackgroundResource extends Resource
{
    protected static ?string $model = GiftCardBackground::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $navigationLabel = 'Gift Card Backgrounds';
    protected static ?string $label = 'Gift Card Background';
    protected static ?string $pluralLabel = 'Gift Card Backgrounds';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Description')
                ->maxLength(500)
                ->rows(3),

            SpatieMediaLibraryFileUpload::make('background_image')
                ->collection('gift_card_backgrounds')
                ->label('Background Image')
                ->required()
                ->maxFiles(1)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'])
                ->maxSize(5120) // 5MB
                ->helperText('Upload a high-quality image for gift card backgrounds. Recommended size: 800x600px or larger.'),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->helperText('Only active backgrounds will be available to users.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable(),

                SpatieMediaLibraryImageColumn::make('background_image')
                    ->collection('gift_card_backgrounds')
                    ->conversion('small')
                    ->label('Image')
                    ->size(60),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('uploadedBy.name')
                    ->label('Uploaded By')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => \App\Filament\Admin\Resources\GiftCardBackgroundResource\Pages\ListGiftCardBackgrounds::route('/'),
            'create' => \App\Filament\Admin\Resources\GiftCardBackgroundResource\Pages\CreateGiftCardBackground::route('/create'),
            'edit' => \App\Filament\Admin\Resources\GiftCardBackgroundResource\Pages\EditGiftCardBackground::route('/{record}/edit'),
            'view' => \App\Filament\Admin\Resources\GiftCardBackgroundResource\Pages\ViewGiftCardBackground::route('/{record}'),
        ];
    }
}
