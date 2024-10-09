<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Requests\Vendor\V3_1\DoctorRequest;
use App\Http\Requests\Vendor\V3_1\UpdateDoctorRequest;
use App\Http\Resources\Vendor\V3_1\DoctorCollection;
use App\Http\Resources\Vendor\V3_1\DoctorResource;
use App\Services\V3\UserService;
use App\Services\V3_1\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends ApiVendorController
{
    private $userService;

    public function __construct(VendorRepositoryInterface $vendorRepository, VendorService $vendorService, UserService $userService)
    {
        parent::__construct($vendorRepository, $vendorService);

        $this->userService = $userService;
    }

    private function authVendor()
    {
        return $this->user->vendor;
    }

    public function list(Request $request)
    {
        abort_unless(($request->vendor == $this->authVendor()->id), 403, message: 'Invalid Provider Id');

        $request->merge(['parent_id' => $this->authVendor()->id]);

        $results = parent::index($request);

        return DoctorCollection::make($results);
    }

    public function add(DoctorRequest $request)
    {
        DB::beginTransaction();
        // create user
        $data = $this->userService->handleUserData($request->validated());

        $user = $this->userService->create($data);

        // create vendor
        $record = $this->vendorService->addDoctor($request->validated() + ['parent_id' => $this->authVendor()->id], $user);

        DB::commit();

        return DoctorResource::make($record)
            ->additional([
                'success' => true,
                'message' => 'Doctor Added Successfully',
            ]);
    }

    public function edit(UpdateDoctorRequest $request, $vendorId, $doctorId)
    {
        $vendor = $this->vendorRepository->getOneOrFail($doctorId);

        abort_unless(($vendorId == $vendor->parent_id), 403, message: 'Invalid Doctor Id');

        DB::beginTransaction();
        // handle user data
        $data = $this->userService->handleUserData($request->validated());
        // update vendor data
        $vendor = $this->vendorService->editDoctor($request->validated() + ['parent_id' => $this->authVendor()->id], $vendor);

        // update user data
        $user = $vendor->staff->first();
        $this->userService->update($user, $data);

        // update email for user vendor relation
        if (isset($data['email'])) {
            $user->pivot->update(['email' => $data['email']]);
        }

        DB::commit();

        return DoctorResource::make($vendor)
            ->additional([
                'success' => true,
                'message' => 'Doctor Added Successfully',
            ]);
    }

    public function show(Request $request, $vendor, $doctor)
    {
        abort_unless(($vendor == $this->authVendor()->id), 403, message: 'Invalid Provider Id');

        $record = parent::single($request, $doctor);

        abort_unless(($vendor == $record->parent_id), 403, message: 'Invalid Doctor Id');

        return DoctorResource::make($record)
            ->additional([
                'success' => true,
                'message' => 'Doctor Details Fetched Successfully',
            ]);
    }
}
