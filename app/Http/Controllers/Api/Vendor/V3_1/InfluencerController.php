<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\InfluencerController as ApiInfluencerController;
use App\Http\Requests\V3\InfluencerRequest;
use App\Http\Resources\Vendor\V3_1\InfluencerCollection;
use App\Http\Resources\Vendor\V3_1\InfluencerResource;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfluencerController extends ApiInfluencerController
{
    public function index(Request $request, Vendor $vendor)
    {
        $records = parent::indexCommon($request, $vendor);

        return InfluencerCollection::make($records)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function store(InfluencerRequest $request, Vendor $vendor)
    {
        try {
            return DB::transaction(function () use ($request, $vendor) {
                $record = parent::createCommon($request, $vendor);

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
