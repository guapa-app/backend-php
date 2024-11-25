<?php

namespace App\Filament\Admin\Resources\Info;

use App\Filament\Admin\Resources\Info\TaxonomyResource\Actions;
use App\Filament\Admin\Resources\Info\TaxonomyResource\Pages;
use App\Models\Taxonomy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaxonomyResource extends Resource
{
    use Translatable;

    protected static ?string $model = Taxonomy::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Info';

    protected static ?string $label = 'Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Set $set, $state) use ($form) {
                        if ($form->getOperation() === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description'),
                Forms\Components\TextInput::make('fees')
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('fixed_price', null))
                    ->requiredWithout('fixed_price')
                    ->numeric()
                    ->visible(fn (callable $get) => $get('type') !== 'special'),
                Forms\Components\Select::make('type')
                    ->options([
                        'category' => 'Products',
                        'specialty' => 'Procedures',
                        'blog_category' => 'Blog',
                    ])
                    ->reactive()
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('fixed_price')
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('fees', null))
                    ->requiredWithout('fees')
                    ->numeric()
                    ->visible(fn (callable $get) => $get('type') !== 'special'),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->native(false)
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title_en_ar}")
                    ->relationship('parent', 'title', function (Builder $query, callable $get) {
                        return $query->where('type', $get('type'));
                    })
                    ->preload()
                    ->hint('select type before assign parent category')
                    ->searchable(),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->minValue(1),
                Forms\Components\SpatieMediaLibraryFileUpload::make('media')
                    ->collection('taxonomy_icons'),

                Forms\Components\Radio::make('is_published')
                    ->boolean()
                    ->default(false)
                    ->label('Published'),

                Forms\Components\Repeater::make('appointmentForms')
                    ->label('Appointment Forms')
                    ->relationship('appointmentFormTaxonomy')
                    ->schema([
                        Forms\Components\Select::make('appointment_form_id')
                            ->label('Select Form')
                            ->relationship('appointmentForm', 'type')
                            ->native(false)
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->type->value} - {$record->key}")
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('media')
                    ->label('Icon')
                    ->collection('taxonomy_icons'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fees')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fixed_price')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('products_counter')
                    ->label('Items Counter')
                    ->numeric(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Publish'),
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
            ->headerActions([
                Actions\ClearSortAction::make('clear-sort'),
                Actions\RandomizeSortAction::make('randomize-sort'),
                Actions\RandomizeMissingSortOrderAction::make('randomize-missing-sort'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaxonomies::route('/'),
            'create' => Pages\CreateTaxonomy::route('/create'),
            'edit' => Pages\EditTaxonomy::route('/{record}/edit'),
        ];
    }
}
