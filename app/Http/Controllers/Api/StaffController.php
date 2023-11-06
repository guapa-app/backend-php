<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Requests\StaffRequest;
use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Staff
 */
class StaffController extends BaseApiController
{
    private $vendorRepository;

    private $userRepository;

    private $vendorService;

    public function __construct(
        VendorRepositoryInterface $vendorRepository,
        UserRepositoryInterface $userRepository,
        VendorService $vendorService
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->userRepository = $userRepository;
        $this->vendorService = $vendorService;
    }

    /**
     * Get vendor staff.
     *
     * @responseFile 200 responses/staff/list.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 403 scenario="Unauthorized to list staff of this vendor" responses/errors/403.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->validate($request, [
            'vendor_id' => 'required|integer|exists:vendors,id',
        ]);

        $vendor = $this->vendorRepository->getOneOrFail($data['vendor_id']);

        $this->validateVendorManager($vendor);

        return $vendor->staff()->get();
    }

    /**
     * Create staff.
     *
     * @responseFile 200 responses/staff/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 403 scenario="Unauthorized to add staff to this vendor" responses/errors/403.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     **/
    public function create(StaffRequest $request)
    {
        $data = $request->validated();

        $vendor = $this->vendorRepository->getOneOrFail($data['vendor_id']);

        $this->validateVendorManager($vendor);

        return $this->vendorService->addStaff($vendor, $data);
    }

    /**
     * Update staff.
     *
     * @responseFile 200 responses/staff/create.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 403 scenario="Unauthorized to update staff of this vendor" responses/errors/403.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     **/
    public function update(StaffRequest $request, $userId)
    {
        $data = $request->validated();

        $userToUpdate = $this->userRepository->getOneOrFail($userId);
        $vendor = $this->vendorRepository->getOneOrFail($data['vendor_id']);

        $this->validateVendorManager($vendor);

        return $this->vendorService->updateSingleStaff($vendor, $userToUpdate, $data);
    }

    /**
     * Delete staff.
     *
     * @responseFile 200 responses/staff/delete.json
     * @responseFile 401 scenario="Unauthenticated" responses/errors/401.json
     * @responseFile 403 scenario="Unauthorized to delete staff of this vendor" responses/errors/403.json
     * @responseFile 404 scenario="Vendor not found" responses/errors/404.json
     **/
    public function delete($userId, $vendorId)
    {
        $vendor = $this->vendorRepository->getOneOrFail($vendorId);

        $this->validateVendorManager($vendor);

        return $this->vendorService->deleteStaff((int) $userId, $vendor);
    }

    /**
     * Validate that current user manages given vendor.
     *
     * @param Vendor $vendor
     * @return void
     */
    public function validateVendorManager($vendor): void
    {
        $user = auth()->user();

        if (!$vendor->hasManager($user)) {
            abort(403, 'You must be a manager to manage staff of this vendor');
        }
    }
}
