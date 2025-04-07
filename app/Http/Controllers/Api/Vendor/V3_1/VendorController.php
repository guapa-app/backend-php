<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\VendorRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Vendor\CreateVendorRequest;
use App\Http\Requests\V3_1\Vendor\UpdateVendorRequest;
use App\Http\Requests\V3_1\Vendor\UpdateWorkingDaysRequest;
use App\Http\Requests\Vendor\V3_1\ActivateWalletRequest;
use App\Http\Resources\Vendor\V3_1\VendorProfileResource;
use App\Http\Resources\Vendor\V3_1\WorkDayResource;
use App\Models\Vendor;
use App\Services\VendorService;
use Exception;
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

            return $this->errorJsonRes([],__('api.something_went_wrong'));
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
    public function getWorkingDays(Request $request)
    {
        // Get the vendor's working days
        $type = $request->get('type');
        if ($type && !in_array($type, ['online', 'offline'])) {
            return $this->errorJsonRes([], __('api.invalid_type'));
        }
        // Get the vendor's working days
        $vendor  = $this->user->vendor;
        $this->validateVendorManager($vendor);

        $workingDays = $vendor->workDays()
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->get();

        $formattedWorkingDays = [];
        foreach ($workingDays as $workDay) {
            $dayName = strtolower($workDay->day->getLabel());
            $formattedWorkingDays[$dayName] = [
                'is_active' => $workDay->is_active,
                'from' => $workDay->start_time,
                'to' => $workDay->end_time,
                'type' => $workDay->type,
            ];
        }
        return $this->successJsonRes($formattedWorkingDays, __('api.success'));

    }

    public function updateWorkingDays(UpdateWorkingDaysRequest $request)
    {
        $vendor  = $this->user->vendor;
        $this->validateVendorManager($vendor);
        $data = $request->validated();
        $workingDays = $this->vendorService->updateWorkingDays($vendor , $data);

        $formattedWorkingDays = [];
        foreach ($workingDays as $workDay) {
            $dayName = strtolower($workDay->day->getLabel());
            $formattedWorkingDays[$dayName] = [
                'is_active' => $workDay->is_active,
                'from' => $workDay->start_time,
                'to' => $workDay->end_time,
                'type' => $workDay->type,
            ];
        }

        return $this->successJsonRes($formattedWorkingDays, __('api.updated'));
    }
    public function activateWallet(ActivateWalletRequest $request)
    {
        $vendor  = $this->user->vendor;
        $this->validateVendorManager($vendor);
        $data = $request->validated();
        // check if the vendor has iban cant update it
        if ($vendor->iban && isset($data['iban']) && $data['iban'] != $vendor->iban) {
            return $this->errorJsonRes([],__('api.can_not_update_iban'));
        }
        $record = $this->vendorService->activateWallet($vendor->id , $data);

        return VendorProfileResource::make($record)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }

    /**
     * Validate that current user manages given vendor.
     *
     * @param  Vendor  $vendor
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
