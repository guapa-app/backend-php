<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Resources\Vendor\V3_1\ConsultationCollection;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Contracts\Repositories\ConsultationRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Services\ConsultationService;
use App\Http\Resources\Vendor\V3_1\ConsultationResource;

class ConsultationController extends BaseApiController
{
    protected $consultationRepository;
    protected $consultationService;

    public function __construct(
        ConsultationRepositoryInterface $consultationRepository,
        ConsultationService $consultationService
    ) {
        parent::__construct();
        $this->consultationRepository = $consultationRepository;
        $this->consultationService = $consultationService;
    }

    /**
     * Display a listing of the consultations for the authenticated vendor.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->merge(['vendor_id' => $this->user->managerVendorId()]);
        // Get consultations using repository with request filters
        $consultations = $this->consultationRepository->all($request);

        return ConsultationCollection::make($consultations)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function show(int $id): ConsultationResource
    {
        return ConsultationResource::make($this->consultationRepository->getOneWithRelations($id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Reject a consultation
     *
     * @param int $consultation
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Consultation $consultation)
    {
        try {
            $this->consultationService->reject($consultation);

            return response()->json([
                'success' => true,
                'message' => __('Consultation rejected successfully'),
                'data' => new ConsultationResource($consultation)
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonRes([], $e->getMessage());
        }
    }
}
