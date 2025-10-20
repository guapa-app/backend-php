<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\V3_1\BkamConsultationRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\V3_1\BkamConsultationCollection;
use App\Http\Resources\User\V3_1\BkamConsultationResource;
use App\Services\V3_1\BkamConsultationService;
use Auth;
use Illuminate\Http\Request;

class BkamConsultationController extends BaseApiController
{
    protected $bkamConsultationRepository;
    protected $bkamConsultationService;

    public function __construct(
        BkamConsultationRepositoryInterface $bkamConsultationRepository,
        BkamConsultationService $bkamConsultationService
    ) {
        parent::__construct();
        $this->bkamConsultationRepository = $bkamConsultationRepository;
        $this->bkamConsultationService = $bkamConsultationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->merge(['user_id' => $this->user->id]);
        $consultations = $this->bkamConsultationRepository->all($request);

        return BkamConsultationCollection::make($consultations)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
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
            $consultation = $this->bkamConsultationRepository->find($id);
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
     * Store a newly created consultation in storage.
     *
     * @param ConsultationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ConsultationRequest $request)
    {
        try {
            $user = Auth::user();
            $consultation = $this->bkamConsultationService->createConsultation($request->validated(), $user);

            return BkamConsultationResource::make($consultation)
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
}
