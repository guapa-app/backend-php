<?php

namespace App\Services\V3_1;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Models\Vendor;
use App\Services\VendorService as BaseVendorService;

class VendorService extends BaseVendorService
{
    public function __construct(VendorRepositoryInterface $vendorRepository, UserRepositoryInterface $userRepository)
    {
        parent::__construct($vendorRepository, $userRepository);
    }

    public function addDoctor(array $data, $user): Vendor
    {
        $data['status'] = array_flip(Vendor::STATUSES)['active'];

        $vendor = $this->vendorRepository->create($data);

        if (isset($data['logo'])) {
            $this->updateLogo($vendor, ['logo' => $data['logo']]);
        }

        if (isset($data['specialty_ids'])) {
            $this->updateSpecialties($vendor, $data['specialty_ids']);
        }

        $vendor->users()->create([
            'user_id' => $user->id,
            'role' => 'doctor',
            'email' => $user->email,
        ]);

        $vendor->loadMissing('logo');

        return $vendor;
    }

    public function editDoctor(array $data, $id): Vendor
    {
        if (isset($data['status'])) {
            $data['status'] = (string)array_flip(Vendor::STATUSES)[$data['status']];
        }

        $vendor = $this->vendorRepository->update($id, $data);

        if (isset($data['logo'])) {
            $this->updateLogo($vendor, ['logo' => $data['logo']]);
        }

        if (isset($data['specialty_ids'])) {
            $this->updateSpecialties($vendor, $data['specialty_ids']);
        }

        $vendor->loadMissing('logo');

        return $vendor;
    }
}
