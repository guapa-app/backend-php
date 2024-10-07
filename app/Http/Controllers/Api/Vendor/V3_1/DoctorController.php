<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Requests\Vendor\V3_1\DoctorRequest;
use App\Http\Resources\Vendor\V3_1\DoctorCollection;
use App\Http\Resources\Vendor\V3_1\DoctorResource;
use App\Services\V3\UserService;
use App\Services\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends ApiVendorController
{
    public function __construct(VendorRepositoryInterface $vendorRepository, VendorService $vendorService)
    {
        parent::__construct($vendorRepository, $vendorService);
    }

    public function list(Request $request)
    {
        abort_if(($request->vendor == $this->authVendor()->id), 403, message: 'Invalid Doctor Id');

        $request->merge(['parent_id' => $this->authVendor()->id]);

        $results = parent::index($request);

        return DoctorCollection::make($results);
    }

    private function authVendor()
    {
        return $this->user->vendor;
    }

    public function add(DoctorRequest $request)
    {
        DB::beginTransaction();
        // create user
        $userService = app(UserService::class);
        $data = $userService->handleUserData($request->validated());
        $data['photo'] = $request->validated()['logo'];
        $userService->create($data);

        // create vendor
        $record = $this->vendorService->create($request->validated() + ['parent_id' => $this->authVendor()->id]);

        DB::commit();

        return DoctorResource::make($record)
            ->additional([
                'success' => true,
                'message' => 'Doctor Added Successfully',
            ]);
    }

    public function show(Request $request, $vendor, $doctor)
    {
        $record = parent::single($request, $doctor);

        abort_if(($this->authVendor()->id != $record->parent_id), 403, message: 'Invalid Doctor Id');

        return DoctorResource::make($record)
            ->additional([
                'success' => true,
                'message' => 'Doctor Details Fetched Successfully',
            ]);
    }
}
