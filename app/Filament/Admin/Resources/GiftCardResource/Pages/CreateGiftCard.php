<?php

namespace App\Filament\Admin\Resources\GiftCardResource\Pages;

use App\Filament\Admin\Resources\GiftCardResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;

class CreateGiftCard extends CreateRecord
{
    protected static string $resource = GiftCardResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['create_profile'])) {
            $user = User::create([
                'name' => $data['profile_name'],
                'email' => $data['profile_email'],
                'phone' => $data['profile_phone'],
                'password' => bcrypt(uniqid('giftcard')), // random password
                'status' => User::STATUS_ACTIVE,
            ]);
            $data['user_id'] = $user->id;
        }
        // Always generate a unique code if not set
        if (empty($data['code'])) {
            $data['code'] = strtoupper(uniqid('GC'));
        }
        // Clean up profile fields so they are not saved to gift_cards
        unset($data['create_profile'], $data['profile_name'], $data['profile_email'], $data['profile_phone']);
        return $data;
    }
}
