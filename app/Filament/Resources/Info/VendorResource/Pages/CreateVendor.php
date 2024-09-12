<?php

namespace App\Filament\Resources\Info\VendorResource\Pages;

use App\Filament\Resources\Info\VendorResource;
use App\Services\V3\UserService;
use Filament\Resources\Pages\CreateRecord;

class CreateVendor extends CreateRecord
{
    protected static string $resource = VendorResource::class;

    protected $user;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userService = app(UserService::class);
        // handle user date to manage profile.
        $data = $userService->handleUserData($data);
        // create user
        $this->user = $userService->create($data);

        $data['parent_id'] = auth()->user()->vendor?->id;

        return $data;
    }

    public function create(bool $another = false): void
    {
        parent::create($another);

        // vendor
        $this->getRecord()->users()->create([
            'user_id' => $this->user->id,
            'role' => 'doctor',
            'email' => $this->user->email,
        ]);
    }

    public function mount(): void
    {
        abort_if(auth()->user()->Vendor->isChild(), 403);
    }
}
