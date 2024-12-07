<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Models\Appointment;
use App\Models\User;
use App\Models\UserVendor;
use App\Models\Vendor;
use App\Models\WorkDay;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * Vendor service.
 */
class VendorService
{
    protected $vendorRepository;
    protected $userRepository;

    public function __construct(VendorRepositoryInterface $vendorRepository, UserRepositoryInterface $userRepository)
    {
        $this->vendorRepository = $vendorRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Create new vendor with relations.
     *
     * @param array $data
     * @return Vendor
     */
    public function create(array $data): Vendor
    {
        /*
        * overwrite default status value from migration file.
        * should remove default values from migrations
        */
        return DB::transaction(function () use ($data) {
            $data['status'] = array_flip(Vendor::STATUSES)['active'];
            $data['country_id'] = auth()->user()->country_id;

            // Create vendor
            $vendor = $this->vendorRepository->create($data);

            // Update logo
            if (isset($data['logo'])) {
                $this->updateLogo($vendor, ['logo' => $data['logo']]);
            }

            if (isset($data['specialty_ids'])) {
                $this->updateSpecialties($vendor, $data['specialty_ids']);
            }

            if (isset($data['address'])) {
                $this->createAddress($vendor, $data['address']);
            }

            if (!$this->vendorRepository->isAdmin()) {
                $vendor->users()->create([
                    'user_id' => auth()->id(),
                    'role' => 'manager',
                    'email' => auth()->user()->email,
                ]);

                auth()->user()->assignRole('manager');
            }

            $this->updateWorkingDaysAndAppointments($vendor, $data);

            $this->updateStaff($vendor, $data);

            $vendor->loadMissing('staff', 'logo', 'addresses', 'workDays');

            return $vendor;
        });
    }

    public function addStaff(Vendor $vendor, $data): User
    {
        $data = $this->normalizeStaffData($vendor, $data);
        $user = $this->userRepository->create($data);

        $user->assignRole($data['role']);
        $vendor->users()->create([
            'user_id' => $user->id,
            'role' => $data['role'],
            'email' => $user->email,
        ]);

        return $user;
    }

    public function updateSingleStaff(Vendor $vendor, User $user, array $data): User
    {
        // If user password is still null, this user has not gained control over this account yet.
        // and it is managed by the vendor
        if ($user->password == null) {
            $user->update($data);
        }

        $vendor->users()->where([
            'user_id' => $user->id,
        ])->update([
            'role' => $data['role'],
            'email' => $data['email'] ?? $user->email,
        ]);

        $newRoles = UserVendor::where('user_id', $user->id)->pluck('role')->toArray();

        if ($user->hasRole('patient')) {
            $newRoles[] = 'patient';
        }

        $user->syncRoles($newRoles);

        return $user;
    }

    public function normalizeStaffData(Vendor $vendor, array $data): array
    {
        if (!isset($data['email'])) {
            $data['email'] = 'vendor-' . $vendor->id . '-' . time() . '@cosmo.com';
        }

        if (!isset($data['role'])) {
            $data['role'] = 'doctor';
        }

        return $data;
    }

    public function update($id, array $data): Vendor
    {
        // Update vendor
        $vendor = $this->vendorRepository->update($id, $data);

        // Update logo
        if (isset($data['remove_logo'])) {
            $logoData = Arr::only($data, ['logo', 'remove_logo']);
        } else {
            $logoData = Arr::only($data, ['logo']);
        }
        $this->updateLogo($vendor, $logoData);

        $this->updateStaff($vendor, $data);

        $this->updateWorkingDaysAndAppointments($vendor, $data);

        if (isset($data['specialty_ids'])) {
            $this->updateSpecialties($vendor, $data['specialty_ids']);
        }

        $vendor->loadMissing('staff', 'logo', 'addresses', 'workDays');

        return $vendor;
    }

    public function deleteStaff($userId, Vendor $vendor): void
    {
        if ($vendor->users()->count() === 1) {
            abort(403, 'Vendor must have at least one staff member');
        }

        $vendor->users()->where('user_id', $userId)->delete();

        $user = $this->userRepository->getOne($userId);
        if ($user && $user->password == null) {
            $user->delete();
        }
    }

    public function updateLogo(Vendor $vendor, array $data): Vendor
    {
        if (isset($data['logo'])) {
            if ($data['logo'] instanceof UploadedFile) {
                $vendor->addMedia($data['logo'])->toMediaCollection('logos');
            } elseif (is_string($data['logo']) && str_contains($data['logo'], ';base64')) {
                $vendor->addMediaFromBase64($data['logo'])->toMediaCollection('logos');
            }
        } elseif ($this->vendorRepository->isAdmin() && !isset($data['logo']) || isset($data['remove_logo']) && $data['remove_logo'] === true) {
            // Delete all vendor media
            // As there is only one collection - logos
            $vendor->media()->delete();
        }

        return $vendor;
    }

    public function updateSpecialties(Vendor $vendor, array $specialties): Vendor
    {
        $vendor->setTaxonomies($specialties, 'specialty');
        $vendor->load('specialties');

        return $vendor;
    }

    /**
     * Create vendor address.
     * @param Vendor $vendor
     * @param array $data
     * @return Vendor
     */
    public function createAddress(Vendor $vendor, array $data): Vendor
    {
        $address = $vendor->addresses()->create($data);

        return $vendor;
    }

    /**
     * Update vendor staff.
     * @param Vendor $vendor
     * @param array $data
     * @return Vendor
     */
    public function updateStaff(Vendor $vendor, array $data): Vendor
    {
        if (!empty($data['staff'])) {
            $staff = collect($data['staff']);
            $newUserIds = $staff->pluck('user_id')->toArray();
            $vendor->users()->whereNotIn('user_id', $newUserIds)->delete();
            foreach ($staff as $employee) {
                $userVendor = $vendor->users()->updateOrCreate([
                    'user_id' => $employee['user_id'],
                    'vendor_id' => $vendor->id,
                ], [
                    'role' => $employee['role'],
                    'email' => $employee['email'] ?? null,
                ]);

                $newRoles = UserVendor::where('user_id', $userVendor->user_id)->pluck('role')->toArray();
                if ($userVendor->user->hasRole('patient')) {
                    $newRoles[] = 'patient';
                }

                $userVendor->user->syncRoles($newRoles);
            }
        } elseif ($this->vendorRepository->isAdmin() && !isset($data['staff'])) {
            $vendor->users()->delete();
        }

        $vendor->load('staff');

        return $vendor;
    }

    public function updateWorkingDaysAndAppointments(Vendor $vendor, array $data): Vendor
    {
        if (isset($data['work_days'])) {
            $vendor->workDays()->delete();
            $workDays = array_map(function ($day) use ($vendor) {
                return [
                    'vendor_id' => $vendor->id,
                    'day' => $day,
                ];
            }, $data['work_days']);

            WorkDay::insert($workDays);
        }

        if (isset($data['appointments'])) {
            $vendor->appointments()->delete();
            $appointments = array_map(function ($appointment) use ($vendor) {
                return [
                    'vendor_id' => $vendor->id,
                    'from_time' => $appointment['from_time'],
                    'to_time' => $appointment['to_time'],
                ];
            }, $data['appointments']);

            Appointment::insert($appointments);
        }

        return $vendor;
    }

    public function share(int $id): int
    {
        return (int) Redis::hincrby("vendor:{$id}", 'shares_count', 1);
    }

    public function view(int $id): int
    {
        return (int) Redis::hincrby("vendor:{$id}", 'views_count', 1);
    }

    public function addSocialMedia(Vendor $vendor, $data)
    {
        return $vendor->socialMedia()
            ->attach(
                $data['social_media_id'],
                [
                    'link' => $data['link'],
                ]
            );
    }

    public function updateSocialMedia(Vendor $vendor, $socialMediaId, $data)
    {
        return $vendor->socialMedia()
            ->updateExistingPivot(
                $socialMediaId,
                [
                    'link' => $data['link'],
                ]
            );
    }

    public function deleteSocialMedia(Vendor $vendor, $socialMediaId)
    {
        return $vendor->socialMedia()->detach([
            $socialMediaId,
        ]);
    }
}
