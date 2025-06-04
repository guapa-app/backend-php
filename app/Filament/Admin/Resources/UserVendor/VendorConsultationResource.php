<?php

namespace App\Filament\Admin\Resources\UserVendor;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vendor;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Consultation;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Admin\Resources\UserVendor\VendorResource\RelationManagers;
use App\Filament\Admin\Resources\UserVendor\VendorConsultationResource\Pages;

class VendorConsultationResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User & Vendor';

    protected static ?string $navigationLabel = 'Consultations Vendors Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Vendor Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->label('Email')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->label('Phone')
                            ->maxLength(20),
                        Forms\Components\Toggle::make('accept_online_consultation')
                            ->label('Accept Online Consultations')
                            ->helperText('Enable this to allow vendor to accept online consultations')
                            ->required(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logos')
                            ->label('Logo')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Consultation Settings')
                    ->schema([
                        Forms\Components\Textarea::make('about')
                            ->label('About')
                            ->maxLength(1000)
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('consultation_fee')
                            ->numeric()
                            ->prefix('$')
                            ->label('Base Consultation Fee'),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logos')
                    ->label('Logo')
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl(url('path/to/default-logo.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Vendor Name'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->label('Phone'),
                Tables\Columns\BooleanColumn::make('accept_online_consultation')
                    ->label('Accepts Consultations')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultations_count')
                    ->label('Total Consultations')
                    ->counts('consultations')
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultation_statuses')
                    ->label('Consultation Statuses')
                    ->getStateUsing(function (Vendor $record) {
                        $statuses = $record->consultations()
                            ->groupBy('status')
                            ->selectRaw('status, COUNT(*) as count')
                            ->pluck('count', 'status')
                            ->mapWithKeys(function ($count, $status) {
                                return [$status => $count];
                            });
                        return collect([
                            Consultation::STATUS_PENDING => 0,
                            Consultation::STATUS_SCHEDULED => 0,
                            Consultation::STATUS_CONFIRMED => 0,
                            Consultation::STATUS_COMPLETED => 0,
                            Consultation::STATUS_CANCELLED => 0,
                            Consultation::STATUS_REJECTED => 0,
                        ])->merge($statuses)->map(function ($count, $status) {
                            return ucfirst(str_replace('_', ' ', $status)) . ': ' . $count;
                        })->implode(', ');
                    })
                    ->wrap(),
                Tables\Columns\TextColumn::make('about')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state;
                    })
                    ->label('About')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('accept_online_consultation')
                    ->options([
                        0 => 'Disabled',
                        1 => 'Active',
                    ])
                    ->label('Consultation Status'),
                SelectFilter::make('consultation_count')
                    ->options([
                        'has_consultations' => 'Has Consultations',
                        'no_consultations' => 'No Consultations',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'has_consultations') {
                            return $query->has('consultations');
                        }
                        if ($data['value'] === 'no_consultations') {
                            return $query->doesntHave('consultations');
                        }
                        return $query;
                    })
                    ->label('Consultation Count'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('toggle_consultation_status')
                        ->label(fn(Vendor $record) => $record->accept_online_consultation ? 'Disable Consultations' : 'Enable Consultations')
                        ->icon(fn(Vendor $record) => $record->accept_online_consultation ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn(Vendor $record) => $record->accept_online_consultation ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->action(function (Vendor $record) {
                            $record->update(['accept_online_consultation' => !$record->accept_online_consultation]);
                            Notification::make()
                                ->title($record->accept_online_consultation ? 'Consultations Enabled' : 'Consultations Disabled')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('update_details')
                        ->label('Update Details')
                        ->icon('heroicon-o-pencil')
                        ->form([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                                ->collection('logos')
                                ->label('Logo')
                                ->image()
                                ->maxSize(2048)
                                ->imageEditor(),
                            Forms\Components\Textarea::make('about')
                                ->label('About')
                                ->maxLength(1000)
                                ->rows(4),
                        ])
                        ->action(function (Vendor $record, array $data) {
                            if (isset($data['about'])) {
                                $record->update(['about' => $data['about']]);
                            }
                            if (isset($data['logo'])) {
                                $record->clearMediaCollection('logos');
                                $record->addMedia($data['logo'])->toMediaCollection('logos');
                            }
                            Notification::make()
                                ->title('Vendor details updated')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\ViewAction::make()
                        ->url(fn(Vendor $record): string => static::getUrl('view', ['record' => $record])),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('enable_consultations')
                        ->label('Enable Consultations')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['accept_online_consultation' => true]);
                            Notification::make()
                                ->title('Consultations Enabled for Selected Vendors')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('disable_consultations')
                        ->label('Disable Consultations')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each->update(['accept_online_consultation' => false]);
                            Notification::make()
                                ->title('Consultations Disabled for Selected Vendors')
                                ->danger()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\WorkDaysRelationManager::class,
            RelationManagers\ConsultationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendorConsultation::route('/'),
            'edit' => Pages\EditVendorConsultation::route('/{record}/edit'),
            'view' => Pages\ViewVendorConsultation::route('/{record}'),
        ];
    }
}