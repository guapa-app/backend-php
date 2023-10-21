<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Requests\VendorRequest;
use App\Services\VendorService;
use Illuminate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @group Vendors
 */
class VendorController extends BaseApiController
{
    private $vendorRepository;
    private $vendorService;

    public function __construct(VendorRepositoryInterface $vendorRepository, VendorService $vendorService)
    {
        parent::__construct();

        $this->vendorRepository = $vendorRepository;
        $this->vendorService = $vendorService;
    }

    /**
     * List vendors
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/vendors/list.json
     * @responseFile 200 scenario="Location search response" responses/vendors/list-with-location-filter.json
     *
     * @queryParam user_id integer Get user vendors. Example: 3
     * @queryParam specialty_ids[] array Get vendors with specific specialties. Example: 1
     * @queryParam specialty_ids[].* integer Get vendors with specific specialties. Example: 1
     * @queryParam keyword String to search vendors. Example: Hospital xxx
     * @queryParam lat Filter vendors by location lat and lng. Example: 30.2563
     * @queryParam lng Filter vendors by location lat and lng. Example: 31.9891
     * @queryParam distance maximum distance for location filter in Km. Example: 50
     * @queryParam page number for pagination Example: 2
     * @queryParam perPage Results to fetch per page Example: 15
     *
     * @param Request $request
     * @return Model
     */
    public function index(Request $request)
    {
        return $this->vendorRepository->all($request);
    }

    /**
     * Register vendor
     *
     * @responseFile 200 responses/vendors/create.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @param VendorRequest $request
     */
    public function create(VendorRequest $request)
    {
        return $this->vendorService->create($request->validated());
    }

    /**
     * Update vendor
     *
     * @responseFile 200 responses/vendors/update.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     * @responseFile 403 scenario="User not authorized to update vendor" responses/errors/403.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     *
     * @authenticated
     * @urlParam id required Vendor id
     *
     * @param VendorRequest $request
     * @param int $id
     * @return Model
     */
    public function update(VendorRequest $request, $id)
    {
        return $this->vendorService->update($id, $request->validated());
    }

    /**
     * Get vendor details
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/vendors/details.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     *
     * @urlParam id required Vendor id
     *
     * @param Request $request
     * @param int $id
     * @return Model
     */
    public function single(Request $request, $id)
    {
        $vendor = $this->vendorRepository->getOneWithRelations($id);

        if ('enduser' === strtolower($request->header('X-App-Type'))) {
            $this->vendorService->view($id);
        }

        $vendor->about = strip_tags($vendor->about);

        return $vendor;
    }

    /**
     * Share vendor
     *
     * @responseFile 200 responses/vendors/share.json
     *
     * @urlParam id integer required Vendor id. Example: 1
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function share(Request $request, $id)
    {
        $sharesCount = $this->vendorService->share((int)$id);

        return ['shares_count' => $sharesCount];
    }
}
