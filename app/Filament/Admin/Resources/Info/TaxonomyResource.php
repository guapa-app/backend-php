<?php

namespace App\Filament\Admin\Resources\Info;

use App\Filament\Admin\Resources\Info\TaxonomyResource\Pages;
use App\Models\Taxonomy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                Forms\Components\TextInput::make('fees')
                    ->rules([ 'prohibited_if:fixed_price,true'])
//                    ->requiredIf('fixed_price', null)
                    ->numeric(),
                Forms\Components\TextInput::make('fixed_price')
                    ->rules([ 'prohibited_if:fees,true'])

//                    ->requiredIf('fees', null)
                    ->numeric(),
                Forms\Components\TextInput::make('description'),
                Forms\Components\Select::make('type')
                    ->options([
                        'category' => 'Products',
                        'specialty' => 'Procedures',
                        'blog_category' => 'Blog',
                    ])
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('parent_id')
                    ->native(false)
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->title}")
                    ->relationship('parent', 'title'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fees')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fixed_price')
                    ->searchable(),
                Tables\Columns\TextColumn::make('font_icon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable(),
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
