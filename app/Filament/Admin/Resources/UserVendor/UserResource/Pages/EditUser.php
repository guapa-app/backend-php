<?php

namespace App\Filament\Admin\Resources\UserVendor\UserResource\Pages;

use App\Filament\Admin\Resources\UserVendor\UserResource;
use App\Filament\Admin\Resources\UserVendor\UserResource\Widgets\UserOrderStats;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make('password');
        }

        if (isset($data['email_verified_at']) && !isset($data['email'])) {
            $data['email_verified_at'] = null;
        }

        $record->update($data);

        if (array_key_exists('name', $record->getChanges())) {
            $nameParts = explode(' ', $data['name']);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $record->profile()->update([
                'firstname' => $firstName,
                'lastname' => $lastName,
            ]);
        }

        return $record;
    }
}
