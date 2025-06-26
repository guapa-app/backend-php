<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\GiftCardSetting;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Facades\Log;

class GiftCardSettingResource extends Resource
{
    protected static ?string $model = GiftCardSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $navigationLabel = 'Gift Card Settings';
    protected static ?string $label = 'Gift Card Setting';
    protected static ?string $pluralLabel = 'Gift Card Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('key')
                        ->label('Setting Key')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Unique identifier for this setting'),

                    Select::make('type')
                        ->label('Value Type')
                        ->options([
                            'string' => 'String',
                            'array' => 'Array',
                            'boolean' => 'Boolean',
                            'integer' => 'Integer',
                        ])
                        ->required()
                        ->reactive()
                        ->helperText('Type of value this setting stores'),

                    Textarea::make('description')
                        ->label('Description')
                        ->rows(2)
                        ->helperText('Description of what this setting controls'),
                ]),

            Section::make('Value Configuration')
                ->schema([
                    // String value
                    TextInput::make('value_string')
                        ->label('String Value')
                        ->visible(fn($get) => $get('type') === 'string')
                        ->helperText('Enter the string value'),

                    // Integer value
                    TextInput::make('value_integer')
                        ->label('Integer Value')
                        ->numeric()
                        ->visible(fn($get) => $get('type') === 'integer')
                        ->helperText('Enter the integer value'),

                    // Boolean value
                    Toggle::make('value_boolean')
                        ->label('Boolean Value')
                        ->visible(fn($get) => $get('type') === 'boolean')
                        ->helperText('Toggle the boolean value'),

                    // Array value - Simple array
                    Repeater::make('value_array_simple')
                        ->label('Array Values')
                        ->schema([
                            TextInput::make('item')
                                ->label('Value')
                                ->required(),
                        ])
                        ->visible(fn($get) => $get('type') === 'array' && !in_array($get('key'), [
                            GiftCardSetting::SUGGESTED_AMOUNTS,
                            GiftCardSetting::BACKGROUND_COLORS,
                            GiftCardSetting::SUPPORTED_CURRENCIES,
                        ]))
                        ->helperText('Add values to the array'),

                    // Array value - Key-value pairs
                    KeyValue::make('value_array_key_value')
                        ->label('Key-Value Pairs')
                        ->visible(fn($get) => $get('type') === 'array' && $get('key') === GiftCardSetting::SUPPORTED_CURRENCIES)
                        ->helperText('Add key-value pairs'),

                    // Special handling for file types
                    Section::make('Allowed File Types')
                        ->schema([
                            Repeater::make('value_allowed_file_types')
                                ->label('File Types')
                                ->schema([
                                    TextInput::make('type')
                                        ->label('MIME Type')
                                        ->required()
                                        ->placeholder('image/jpeg')
                                        ->helperText('Enter MIME type (e.g., image/jpeg, image/png)'),
                                ])
                                ->visible(fn($get) => $get('key') === GiftCardSetting::ALLOWED_FILE_TYPES)
                                ->helperText('Add allowed file types for background images'),
                        ])
                        ->visible(fn($get) => $get('key') === GiftCardSetting::ALLOWED_FILE_TYPES),

                    // Special handling for specific settings
                    Section::make('Background Colors')
                        ->schema([
                            Repeater::make('value_background_colors')
                                ->label('Background Colors')
                                ->schema([
                                    TextInput::make('color')
                                        ->label('Color')
                                        ->required()
                                        ->placeholder('#FF8B85')
                                        ->helperText('Enter hex color code'),
                                ])
                                ->visible(fn($get) => $get('key') === GiftCardSetting::BACKGROUND_COLORS)
                                ->helperText('Add background colors for gift cards'),
                        ])
                        ->visible(fn($get) => $get('key') === GiftCardSetting::BACKGROUND_COLORS),

                    Section::make('Suggested Amounts')
                        ->schema([
                            Repeater::make('value_suggested_amounts')
                                ->label('Suggested Amounts')
                                ->schema([
                                    TextInput::make('amount')
                                        ->label('Amount')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1)
                                        ->helperText('Enter amount value'),
                                ])
                                ->visible(fn($get) => $get('key') === GiftCardSetting::SUGGESTED_AMOUNTS)
                                ->helperText('Add suggested amounts for gift cards'),
                        ])
                        ->visible(fn($get) => $get('key') === GiftCardSetting::SUGGESTED_AMOUNTS),
                ]),

            Section::make('Status')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Only active settings will be used by the system'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Setting Key')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),

                TextColumn::make('value')
                    ->label('Value Preview')
                    ->formatStateUsing(function ($state, $record) {
                        if (is_array($state)) {
                            if (empty($state)) return 'Empty array';
                            $display = implode(', ', array_slice($state, 0, 3));
                            if (count($state) > 3) $display .= ', …';
                            return $display;
                        }
                        if (is_bool($state)) {
                            return $state ? 'Yes' : 'No';
                        }
                        if (is_null($state) || $state === '' || (is_string($state) && trim($state) === '')) {
                            return '—';
                        }
                        $str = (string) $state;
                        return mb_strlen($str) > 30 ? mb_substr($str, 0, 30) . '…' : $str;
                    })
                    ->toggleable(false)
                    ->searchable()
                    ->extraAttributes(['style' => 'max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;']),

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'string',
                        'success' => 'array',
                        'warning' => 'boolean',
                        'danger' => 'integer',
                    ]),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'string' => 'String',
                        'array' => 'Array',
                        'boolean' => 'Boolean',
                        'integer' => 'Integer',
                    ]),
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
            ->defaultSort('key', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\GiftCardSettingResource\Pages\ListGiftCardSettings::route('/'),
            'create' => \App\Filament\Admin\Resources\GiftCardSettingResource\Pages\CreateGiftCardSetting::route('/create'),
            'edit' => \App\Filament\Admin\Resources\GiftCardSettingResource\Pages\EditGiftCardSetting::route('/{record}/edit'),
            'view' => \App\Filament\Admin\Resources\GiftCardSettingResource\Pages\ViewGiftCardSetting::route('/{record}'),
        ];
    }

    public static function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'string':
                    $data['value_string'] = $data['value'] ?? '';
                    break;
                case 'integer':
                    $data['value_integer'] = $data['value'] ?? '';
                    break;
                case 'boolean':
                    $data['value_boolean'] = $data['value'] ?? false;
                    break;
                case 'array':
                    $value = $data['value'] ?? [];
                    // If value is a JSON string, decode it
                    if (is_string($value)) {
                        $decoded = json_decode($value, true);
                        if (is_array($decoded)) {
                            $value = $decoded;
                        }
                    }
                    // Handle special cases for specific setting keys
                    if (isset($data['key'])) {
                        switch ($data['key']) {
                            case GiftCardSetting::BACKGROUND_COLORS:
                                $data['value_background_colors'] = array_map(function($color) {
                                    return ['color' => $color];
                                }, is_array($value) ? $value : []);
                                break;
                            case GiftCardSetting::SUGGESTED_AMOUNTS:
                                $data['value_suggested_amounts'] = array_map(function($amount) {
                                    return ['amount' => $amount];
                                }, is_array($value) ? $value : []);
                                break;
                            case GiftCardSetting::ALLOWED_FILE_TYPES:
                                $data['value_allowed_file_types'] = array_map(function($type) {
                                    return ['type' => $type];
                                }, is_array($value) ? $value : []);
                                break;
                            default:
                                // Detect associative vs. simple array
                                if (is_array($value) && count($value) > 0 && array_keys($value) !== range(0, count($value) - 1)) {
                                    // Associative array (key-value)
                                    $data['value_array_key_value'] = $value;
                                } else {
                                    // Simple/numeric array
                                    $data['value_array_simple'] = array_map(function($item) {
                                        return ['item' => $item];
                                    }, is_array($value) ? $value : []);
                                }
                                break;
                        }
                    } else {
                        // Fallback for array type without specific key
                        if (is_array($value) && count($value) > 0 && array_keys($value) !== range(0, count($value) - 1)) {
                            $data['value_array_key_value'] = $value;
                        } else {
                            $data['value_array_simple'] = array_map(function($item) {
                                return ['item' => $item];
                            }, is_array($value) ? $value : []);
                        }
                    }
                    break;
            }
        }
        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Move the correct field into value for saving
        switch ($data['type']) {
            case 'string':
                $data['value'] = $data['value_string'] ?? '';
                break;
            case 'integer':
                $data['value'] = $data['value_integer'] ?? '';
                break;
            case 'boolean':
                $data['value'] = $data['value_boolean'] ?? false;
                break;
            case 'array':
                if (isset($data['key'])) {
                    switch ($data['key']) {
                        case GiftCardSetting::BACKGROUND_COLORS:
                            $data['value'] = array_map(function($item) {
                                return $item['color'] ?? '';
                            }, $data['value_background_colors'] ?? []);
                            break;
                        case GiftCardSetting::SUGGESTED_AMOUNTS:
                            $data['value'] = array_map(function($item) {
                                return $item['amount'] ?? 0;
                            }, $data['value_suggested_amounts'] ?? []);
                            break;
                        case GiftCardSetting::ALLOWED_FILE_TYPES:
                            $data['value'] = array_map(function($item) {
                                return $item['type'] ?? '';
                            }, $data['value_allowed_file_types'] ?? []);
                            break;
                        default:
                            // If key-value pairs are present, use them
                            if (!empty($data['value_array_key_value']) && is_array($data['value_array_key_value'])) {
                                $data['value'] = $data['value_array_key_value'];
                            } else {
                                // Otherwise, use simple array
                                $data['value'] = array_map(function($item) {
                                    return $item['item'] ?? '';
                                }, $data['value_array_simple'] ?? []);
                            }
                            break;
                    }
                } else {
                    if (!empty($data['value_array_key_value']) && is_array($data['value_array_key_value'])) {
                        $data['value'] = $data['value_array_key_value'];
                    } else {
                        $data['value'] = array_map(function($item) {
                            return $item['item'] ?? '';
                        }, $data['value_array_simple'] ?? []);
                    }
                }
                break;
        }
        // Clean up UI-only fields
        unset(
            $data['value_string'],
            $data['value_integer'],
            $data['value_boolean'],
            $data['value_array_simple'],
            $data['value_background_colors'],
            $data['value_suggested_amounts'],
            $data['value_array_key_value'],
            $data['value_allowed_file_types']
        );
        return $data;
    }
}
