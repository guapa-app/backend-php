<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Vendor\V3_1\CreateVendorRequest;
use App\Http\Requests\Vendor\V3_1\UpdateVendorRequest;
use App\Http\Resources\Vendor\V3_1\VendorProfileResource;
use App\Services\VendorService;
use Exception;

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
    public function create(CreateVendorRequest $request)
    {
        try {
            $vendor = $this->vendorService->create($request->validated());

            return VendorProfileResource::make($vendor)
                ->additional([
                    'success' => true,
                    'message' => __('api.created'),
                ]);
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return $this->errorJsonRes(__('api.something_went_wrong'));
        }
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
