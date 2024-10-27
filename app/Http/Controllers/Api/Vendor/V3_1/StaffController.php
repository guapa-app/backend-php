<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\StaffRequest;
use App\Http\Resources\Vendor\V3_1\UserResource;
use App\Services\VendorService;
use Illuminate\Http\Request;

class StaffController extends BaseApiController
{
    private $userRepository;

    private $vendorService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        VendorService $vendorService
    ) {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->vendorService = $vendorService;
    }
    public function index(Request $request)
    {
        $vendor  = $this->user->vendor;

        $this->validateVendorManager($vendor);

        return UserResource::collection($vendor->staff()->get())
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function create(StaffRequest $request)
    {
        $data = $request->validated();

        $vendor  = $this->user->vendor;

        $this->validateVendorManager($vendor);

        $user = $this->vendorService->addStaff($vendor, $data);
        return UserResource::make($user)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(StaffRequest $request, $userId)
    {
        try {
            $data = $request->validated();
            $vendor  = $this->user->vendor;
            $userToUpdate = $this->userRepository->getOneOrFail($userId);

            $this->validateVendorManager($vendor);
            $this->validateUserBelongsToVendor($vendor, $userToUpdate);

            $user = $this->vendorService->updateSingleStaff($vendor, $userToUpdate, $data);

            return UserResource::make($user)
                ->additional([
                    'success' => true,
                    'message' => __('api.success'),
                ]);
        } catch (\Throwable $th) {
            return $this->errorJsonRes([
                'is_updated' => false,
                'user' => $th->getMessage(),
            ], __('api.contact_support'), 422);
        }
    }

    public function delete($userId)
    {
        $vendor  = $this->user->vendor;
        $this->validateVendorManager($vendor);

        $this->vendorService->deleteStaff((int) $userId, $vendor);

        return $this->successJsonRes([], __('api.staff_deleted'));
    }

    private function validateVendorManager($vendor): void
    {
        $user = auth()->user();

        if (!$vendor->hasManager($user)) {
            abort(403, 'You must be a manager to manage staff of this vendor');
        }
    }
    private function validateUserBelongsToVendor($vendor, $user): void
    {
        if (!$vendor->hasUser($user)) {
            abort(403, 'This user does not belong to this manager');
        }
    }
}
