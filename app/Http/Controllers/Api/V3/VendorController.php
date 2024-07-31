<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Requests\VendorRequest;
use App\Http\Resources\VendorResource;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class VendorController extends ApiVendorController
{
    /**
     * After register a user he can have a vendor access
     * by add vendor data.
     *
     * @param VendorRequest $request
     * @return JsonResponse
     */
    public function create(VendorRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $vendor = parent::create($request);

                return VendorResource::make($vendor)
                    ->additional([
                        'success' => true,
                        'message' => __('api.created'),
                    ]);
            });
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return $this->errorJsonRes(message: 'something went wrong');
        }
    }

    public function update(VendorRequest $request, $id)
    {
        $item = parent::update($request, $id);

        return VendorResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.updated'),
            ]);
    }
}
