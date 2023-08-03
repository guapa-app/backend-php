<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\StaffController as ApiStaffController;
use App\Http\Requests\StaffRequest;
use App\Http\Resources\StaffCollection;
use App\Http\Resources\UserResource;
use Illuminate;
use Illuminate\Http\Request;

class StaffController extends ApiStaffController
{
    public function index(Request $request)
    {
        return StaffCollection::make(parent::index($request))
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);

    }

    public function create(StaffRequest $request)
    {
        return UserResource::make(parent::create($request))
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(StaffRequest $request, $userId)
    {
        return UserResource::make(parent::update($request, $userId))
            ->additional([
                "success" => true,
                'message' => __('api.success'),
            ]);

    }

    public function delete($userId, $vendorId)
    {
        parent::delete($userId, $vendorId);

        return $this->successJsonRes([], __('api.staff_deleted'));
    }
}
