<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\User\V3_1\VendorCollection;
use App\Http\Resources\User\V3_1\VendorDetailsResource;
use App\Http\Resources\User\V3_1\VendorResource;
use App\Services\VendorService;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        if (!($request->has('verified') || $request->has('filter.verified'))) {
            $request->merge(['verified' => 1]);
        }

        $vendors  = $this->vendorRepository->all($request);
        return VendorCollection::make($vendors)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single(Request $request, $id)
    {
        $vendor = $this->vendorRepository->getOneWithRelations($id);

        $this->vendorService->view($id);

        $vendor->about = strip_tags($vendor->about);

        return VendorDetailsResource::make($vendor)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
