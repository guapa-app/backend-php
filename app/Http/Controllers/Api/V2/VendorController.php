<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\VendorController as ApiVendorController;
use App\Http\Requests\VendorRequest;
use App\Http\Resources\VendorCollection;
use App\Http\Resources\VendorResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Vendors
 */
class VendorController extends ApiVendorController
{
    public function index(Request $request)
    {
        $index = parent::index($request);

        return VendorCollection::make($index)
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

    public function create(VendorRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $item = parent::create($request);

                return VendorResource::make($item)
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

    public function share(Request $request, $id)
    {
        $sharesCount = parent::share($request, $id);

        return $this->successJsonRes($sharesCount, __('api.success'));
    }
}
