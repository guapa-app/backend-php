<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Vendor\V3_1\UpdateVendorRequest;
use App\Http\Requests\VendorRequest;
use App\Http\Resources\Vendor\V3_1\VendorProfileResource;
use App\Http\Resources\Vendor\V3_1\VendorResource;
use App\Services\VendorService;

class VendorController extends BaseApiController
{

    protected $vendorRepository;
    protected $vendorService;

    public function __construct(VendorRepositoryInterface $vendorRepository, VendorService $vendorService)
    {
        parent::__construct();

        $this->vendorRepository = $vendorRepository;
        $this->vendorService = $vendorService;
    }

    public function update(UpdateVendorRequest $request)
    {
        // Complete and update vendor data
        $id = $this->user->managerVendorId();
        $record = $this->vendorService->update($id, $request->validated());

        return VendorProfileResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }
}
