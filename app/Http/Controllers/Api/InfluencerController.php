<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\InfluencerRepositoryInterface;
use App\Enums\InfluencerStatus;

class InfluencerController extends BaseApiController
{
    private $InfluencerRepository;

    public function __construct(InfluencerRepositoryInterface $InfluencerRepository)
    {
        parent::__construct();

        $this->InfluencerRepository = $InfluencerRepository;
    }

    public function indexCommon($request, $vendor)
    {
        $request->request->add(['vendor_id' => $vendor->id]);

        return $this->InfluencerRepository->all($request);
    }

    public function createCommon($request, $vendor)
    {
        $data = $request->validated();
        $data['vendor_id'] = $vendor?->id;
        $data['status'] = InfluencerStatus::Pending;
        $data['phone'] ??= $vendor?->phone ?? $this->user->phone;

        return $this->InfluencerRepository->create($data);
    }
}
