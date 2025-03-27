<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Resources\Vendor\V3_1\ConsultationCollection;
use App\Models\Consultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Reject a consultation
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($id)
    {
        try {
            $vendor = Auth::user()->vendor;
            if (!$vendor) {
                return $this->errorJsonRes([], __('Vendor not found'), 404);
            }

            // Use repository to find the consultation
            $consultation = $this->consultationRepository->find($id, $vendor->id);

            if (!$consultation) {
                return $this->errorJsonRes([], __('Consultation not found'), 404);
            }

            // Check if the consultation can be rejected
            if (!$consultation->canReject()) {
                return $this->errorJsonRes([], __('Cannot reject this consultation. It must be at least 6 hours before the appointment time.'), 400);
            }

            // Check if the consultation is already cancelled or rejected
            if ($consultation->status === Consultation::STATUS_CANCELLED) {
                return $this->errorJsonRes([], __('Consultation is already cancelled'), 400);
            }

            if ($consultation->status === Consultation::STATUS_REJECTED) {
                return $this->errorJsonRes([], __('Consultation is already rejected'), 400);
            }

            $consultation->status = Consultation::STATUS_REJECTED;
            $consultation->rejected_at = now();
            $consultation->save();

            // TODO: Send notification to the user about the rejection

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
