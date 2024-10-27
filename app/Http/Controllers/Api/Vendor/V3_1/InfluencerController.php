<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\InfluencerRepositoryInterface;
use App\Enums\InfluencerStatus;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Vendor\InfluencerRequest;
use App\Http\Resources\Vendor\V3_1\InfluencerCollection;
use App\Http\Resources\Vendor\V3_1\InfluencerResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfluencerController extends BaseApiController
{
    private $InfluencerRepository;

    public function __construct(InfluencerRepositoryInterface $InfluencerRepository)
    {
        parent::__construct();

        $this->InfluencerRepository = $InfluencerRepository;
    }
    public function index(Request $request)
    {
        $request->merge(['vendor_id' => $this->user->managerVendorId()]);
        $records = $this->InfluencerRepository->all($request);

        return InfluencerCollection::make($records)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function store(InfluencerRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();
                $vendor = $this->user->vendor;
                $data['vendor_id'] = $vendor->id;
                $data['status'] = InfluencerStatus::Pending;
                $data['phone'] ??= $vendor?->phone ?? $this->user->phone;
                // create influencer
                $record = $this->InfluencerRepository->create($data);

                return InfluencerResource::make($record)
                    ->additional([
                        'success' => true,
                        'message' => __('api.created'),
                    ]);
            });
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return $this->errorJsonRes(message: __('api.error_occurred'));
        }
    }
}
