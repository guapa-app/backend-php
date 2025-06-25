<?php

namespace App\Filament\Admin\Resources\GiftCardResource\Pages;

use App\Filament\Admin\Resources\GiftCardResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewGiftCard extends ViewRecord
{
    protected static string $resource = GiftCardResource::class;

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('code')->label('Gift Card Code')->disabled(),
            Forms\Components\TextInput::make('amount')->label('Amount')->disabled(),
            Forms\Components\TextInput::make('currency')->label('Currency')->disabled(),
            Forms\Components\TextInput::make('status')->label('Status')->disabled(),
            Forms\Components\TextInput::make('display_name')->label('Recipient Name')->disabled(),
            Forms\Components\TextInput::make('display_email')->label('Recipient Email')->disabled(),
            Forms\Components\TextInput::make('display_phone')->label('Recipient Number')->disabled(),
            Forms\Components\TextInput::make('vendor_id')->label('Vendor')->disabled(),
            Forms\Components\TextInput::make('type')->label('Gift Card Type')->disabled(),
            Forms\Components\TextInput::make('product_id')->label('Product/Service')->disabled(),
            Forms\Components\TextInput::make('offer_id')->label('Offer')->disabled(),
            Forms\Components\Textarea::make('message')->label('Message')->disabled(),
            Forms\Components\TextInput::make('background_color')->label('Background Color')->disabled(),
            Forms\Components\TextInput::make('background_image')->label('Background Image')->disabled(),
            Forms\Components\DateTimePicker::make('expires_at')->label('Expires At')->disabled(),
            Forms\Components\DateTimePicker::make('redeemed_at')->label('Redeemed At')->disabled(),
            Forms\Components\DateTimePicker::make('created_at')->label('Created At')->disabled(),
            Forms\Components\DateTimePicker::make('updated_at')->label('Updated At')->disabled(),
        ];
    }
}
