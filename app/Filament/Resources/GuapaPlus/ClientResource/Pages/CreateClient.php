<?php

namespace App\Filament\Resources\GuapaPlus\ClientResource\Pages;

use App\Filament\Resources\GuapaPlus\ClientResource;
use App\Models\VendorClient;
use App\Services\VendorClientService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $vendor = auth()->user()->userVendors->first()->vendor;
        $clientService = app(VendorClientService::class);

        $clientData = $clientService->addClient($vendor, $data);

        $vendorClient = VendorClient::where('vendor_id', $vendor->id)
            ->where('user_id', $clientData['id'])
            ->firstOrFail();
        Notification::make()
            ->title('Client created successfully')
            ->success()
            ->send();

        return $vendorClient;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
