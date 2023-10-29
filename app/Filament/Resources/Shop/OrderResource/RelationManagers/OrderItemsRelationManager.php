<?php

namespace App\Filament\Resources\Shop\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product.title')
                    ->label('Product')
                    ->required()
                    ->columnSpan([
                        'md' => 5,
                    ]),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Unit Price')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required()
                    ->columnSpan([
                        'md' => 3,
                    ]),
                Forms\Components\Select::make('offer_id')
                    ->relationship('offer', 'title'),

            ])
            ->columns(1);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('title'),
                TextEntry::make('quantity'),
                TextEntry::make('amount'),
                TextEntry::make('offer.title'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('offer.title'),
                Tables\Columns\TextColumn::make('offer.discount')->label('Discount %')
            ]);
    }
}
