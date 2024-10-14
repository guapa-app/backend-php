<?php

namespace App\Filament\User\Resources\GuapaPlus\ClientResource\Pages;

use App\Filament\User\Resources\GuapaPlus\ClientResource;
use App\Jobs\ProcessClientImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListClients extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('bulkUpload')
                ->label('Bulk Upload')
                ->form(ClientResource::getBulkUploadFormSchema())
                ->action(function (array $data): void {
//                    $path = Storage::path($data['excel_file'], 'public');
                    $path =  Storage::disk('public')->path($data['excel_file']);

                    // Handle the bulk upload here
                    $vendor = auth()->user()->userVendors->first()->vendor;

                    ProcessClientImport::dispatch($path, $vendor);

                    Notification::make()
                        ->title('Bulk upload initiated')
                        ->body('Your data will be processed shortly.')
                        ->success()
                        ->send();

                }),
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ClientResource::getWidgets();
    }


}
