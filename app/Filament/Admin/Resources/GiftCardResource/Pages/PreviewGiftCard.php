<?php

namespace App\Filament\Admin\Resources\GiftCardResource\Pages;

use App\Models\GiftCard;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Admin\Resources\GiftCardResource;

class PreviewGiftCard extends Page
{
    protected static string $resource = GiftCardResource::class;
    protected static string $view = 'filament.admin.resources.gift-card-resource.pages.preview-gift-card';
    public ?GiftCard $record = null;

    public function mount($record): void
    {
        $this->record = $record;
        $this->form->fill([
            'code' => $record->code,
            'amount' => $record->amount,
            'currency' => $record->currency,
            'gift_type' => $record->gift_type_label,
            'status' => $record->status_label,
            'recipient_name' => $record->recipient_name,
            'message' => $record->message,
            'expires_at' => $record->expires_at,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Gift Card Preview')
                ->schema([
                    Grid::make(2)->schema([
                        // Visual Preview (inject custom Blade view)
                        Section::make('Visual Preview')
                            ->schema([
                                View::make('filament.admin.resources.gift-card-resource.partials.visual-preview')
                                    ->viewData([
                                        'giftCard' => $this->record,
                                    ]),
                            ])
                            ->columnSpan(1),

                        // Details
                        Section::make('Gift Card Details')
                            ->schema([
                                View::make('filament.admin.resources.gift-card-resource.partials.details')
                                    ->viewData([
                                        'giftCard' => $this->record,
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('edit')
                ->label('Edit Gift Card')
                ->url(fn() => route('filament.admin.resources.gift-cards.edit', $this->record))
                ->icon('heroicon-o-pencil'),
        ];
    }
}
