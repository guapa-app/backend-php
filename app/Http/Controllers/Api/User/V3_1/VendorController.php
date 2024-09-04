<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Requests\VendorRequest;
use App\Http\Resources\V3_1\VendorResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorController extends ApiVendorController
{
    public function index(Request $request)
    {
        $index = parent::index($request);

        return VendorResource::make($index)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function single(Request $request, $id)
    {
        $item = parent::single($request, $id);

        return VendorResource::make($item)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * After register a user he can have a vendor access
     * by add vendor data.
     *
     * @param  VendorRequest  $request
     * @return VendorResource|JsonResponse
     */
    public function create(VendorRequest $request)
    {
        try {
            $vendor = parent::create($request);

            return VendorResource::make($vendor)
                ->additional([
                    'success' => true,
                    'message' => __('api.created'),
                ]);
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

    public function share(Request $request, $id)
    {
        $sharesCount = parent::share($request, $id);

        return $this->successJsonRes($sharesCount, __('api.success'));
    }
}
