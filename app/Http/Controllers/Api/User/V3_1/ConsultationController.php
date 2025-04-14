<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Resources\User\V3_1\ConsultationCollection;
use App\Http\Resources\User\V3_1\ConsultationResource;
use App\Models\Consultation;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ConsultationRequest;
use App\Contracts\Repositories\ConsultationRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Services\ConsultationService;
use App\Exceptions\TimeSlotNotAvailableException;

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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $request->merge(['user_id', $user->id]);
        $consultations = $this->consultationRepository->all($request);

        return ConsultationCollection::make($consultations)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Get available time slots for a vendor on a specific date
     *
     * @param int $vendorId
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableTimeSlots($vendorId, $date)
    {
        try {
            $slots = $this->consultationService->getAvailableTimeSlots($vendorId, $date);

            return response()->json([
                'success' => true,
                'message' => __('Available time slots fetched successfully'),
                'data' => [
                    'items' => $slots,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonRes([], $e->getMessage());
        }
    }

    /**
     * Store a newly created consultation in storage.
     *
     * @param ConsultationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ConsultationRequest $request)
    {
        try {
            $user = Auth::user();
            $consultation = $this->consultationService->createConsultation($request->validated(), $user);

            return ConsultationResource::make($consultation)
                ->additional([
                    'success' => true,
                    'message' => __('api.success'),
                ]);
        } catch (\Exception $e) {
            return $this->errorJsonRes([], $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->errorJsonRes([], __('Error processing request: ') . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified consultation.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $consultation = $this->consultationRepository->find($id);
            if (!$consultation) {
                return $this->errorJsonRes([], __('Consultation not found'), 404);
            }

            return response()->json([
                'success' => true,
                'message' => __('Consultation fetched successfully'),
                'data' => $consultation
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonRes([], $e->getMessage());
        }
    }

    /**
     * Cancel a consultation
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        try {
            $consultation = Consultation::findOrFail($id);

            // Check if the consultation belongs to the authenticated user
            if ($consultation->user_id !== Auth::id()) {
                return $this->errorJsonRes([], __('Unauthorized'), 403);
            }

            // Check if the consultation can be cancelled
            if ($consultation->status === 'cancelled') {
                return $this->errorJsonRes([], __('Consultation is already cancelled'), 400);
            }

            // Check if consultation is in the past
            if (Carbon::parse($consultation->appointment_date . ' ' . $consultation->appointment_time)->isPast()) {
                return $this->errorJsonRes([], __('Cannot cancel a past consultation'), 400);
            }

            $consultation->status = 'cancelled';
            $consultation->cancelled_at = now();
            $consultation->save();

            return response()->json([
                'success' => true,
                'message' => __('Consultation cancelled successfully'),
                'data' => $consultation
            ]);
        } catch (\Exception $e) {
            return $this->errorJsonRes([], $e->getMessage());
        }
    }
}
