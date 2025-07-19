<?php

namespace App\Filament\Admin\Resources\GiftCardSettingResource\Pages;

use Filament\Forms\Form;
use App\Models\GiftCardSetting;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Admin\Resources\GiftCardSettingResource;

class ViewGiftCardSetting extends ViewRecord
{
    protected static string $resource = GiftCardSettingResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return GiftCardSettingResource::mutateFormDataBeforeFill($data);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('key')
                        ->label('Setting Key')
                        ->disabled()
                        ->helperText('Unique identifier for this setting'),

                    TextInput::make('type')
                        ->label('Value Type')
                        ->disabled()
                        ->helperText('Type of value this setting stores'),

                    Textarea::make('description')
                        ->label('Description')
                        ->disabled()
                        ->helperText('Description of what this setting controls'),
                ]),

            Section::make('Value Configuration')
                ->schema([
                    // String value
                    TextInput::make('value_string')
                        ->label('String Value')
                        ->visible(fn($get) => $get('type') === 'string')
                        ->disabled()
                        ->helperText('String value stored'),

                    // Integer value
                    TextInput::make('value_integer')
                        ->label('Integer Value')
                        ->visible(fn($get) => $get('type') === 'integer')
                        ->disabled()
                        ->helperText('Integer value stored'),

                    // Boolean value
                    Toggle::make('value_boolean')
                        ->label('Boolean Value')
                        ->visible(fn($get) => $get('type') === 'boolean')
                        ->disabled()
                        ->helperText('Boolean value stored'),

                    // Array value - Simple array
                    Repeater::make('value_array_simple')
                        ->label('Array Values')
                        ->schema([
                            TextInput::make('item')
                                ->label('Value')
                                ->disabled(),
                        ])
                        ->visible(fn($get) => $get('type') === 'array' && !in_array($get('key'), [
                            GiftCardSetting::SUGGESTED_AMOUNTS,
                            GiftCardSetting::BACKGROUND_COLORS,
                            GiftCardSetting::SUPPORTED_CURRENCIES,
                        ]))
                        ->disabled()
                        ->helperText('Array values stored'),

                    // Array value - Key-value pairs
                    KeyValue::make('value_array_key_value')
                        ->label('Key-Value Pairs')
                        ->visible(fn($get) => $get('type') === 'array' && $get('key') === GiftCardSetting::SUPPORTED_CURRENCIES)
                        ->disabled()
                        ->helperText('Key-value pairs stored'),

                    // Special handling for file types
                    Section::make('Allowed File Types')
                        ->schema([
                            Repeater::make('value_allowed_file_types')
                                ->label('File Types')
                                ->schema([
                                    TextInput::make('type')
                                        ->label('MIME Type')
                                        ->disabled(),
                                ])
                                ->visible(fn($get) => $get('key') === GiftCardSetting::ALLOWED_FILE_TYPES)
                                ->disabled()
                                ->helperText('Allowed file types for background images'),
                        ])
                        ->visible(fn($get) => $get('key') === GiftCardSetting::ALLOWED_FILE_TYPES),

                    // Special handling for background colors
                    Section::make('Background Colors')
                        ->schema([
                            Repeater::make('value_background_colors')
                                ->label('Background Colors')
                                ->schema([
                                    TextInput::make('color')
                                        ->label('Color')
                                        ->disabled()
                                        ->helperText('Hex color code'),
                                ])
                                ->visible(fn($get) => $get('key') === GiftCardSetting::BACKGROUND_COLORS)
                                ->disabled()
                                ->helperText('Background colors for gift cards'),
                        ])
                        ->visible(fn($get) => $get('key') === GiftCardSetting::BACKGROUND_COLORS),

                    // Special handling for suggested amounts
                    Section::make('Suggested Amounts')
                        ->schema([
                            Repeater::make('value_suggested_amounts')
                                ->label('Suggested Amounts')
                                ->schema([
                                    TextInput::make('amount')
                                        ->label('Amount')
                                        ->disabled()
                                        ->helperText('Amount value'),
                                ])
                                ->visible(fn($get) => $get('key') === GiftCardSetting::SUGGESTED_AMOUNTS)
                                ->disabled()
                                ->helperText('Suggested amounts for gift cards'),
                        ])
                        ->visible(fn($get) => $get('key') === GiftCardSetting::SUGGESTED_AMOUNTS),
                ]),

            Section::make('Status')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Active')
                        ->disabled()
                        ->helperText('Whether this setting is active'),
                ]),
        ]);
    }
}
