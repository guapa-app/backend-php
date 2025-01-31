<?php

namespace App\Filament\Admin\Resources\Finance\VendorTransactionResource\Pages;

use App\Enums\TransactionStatus;
use App\Filament\Admin\Resources\Finance\VendorTransactionResource;
use App\Models\Vendor;
use App\Models\Wallet;
use App\Services\TransactionService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorTransaction extends CreateRecord
{
    protected static string $resource = VendorTransactionResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $service = app(TransactionService::class);

        try {
            $wallet = Wallet::where('vendor_id', $data['vendor_id'])->firstOrFail();

            if ($data['operation'] === 'Withdrawal' && $wallet->balance < $data['amount']) {
                throw new \Exception(
                    "Insufficient balance. Available: {$wallet->balance}, Requested: {$data['amount']}"
                );
            }
            $data['status'] = TransactionStatus::COMPLETED->value;
            $transaction = $service->createVendorTransaction($data);

            if ($data['operation'] === 'Deposit') {
                $wallet->balance += $data['amount'];
            } else {
                $wallet->balance -= $data['amount'];
            }
            $wallet->save();

            $this->redirect($this->getRedirectUrl());

            return $transaction;

        } catch (\Exception $e) {
            Notification::make()
                ->title('Insufficient Balance')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
