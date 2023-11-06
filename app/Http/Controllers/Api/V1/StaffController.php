<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\StaffController as ApiStaffController;
use App\Http\Requests\StaffRequest;
use Illuminate\Http\Request;

class StaffController extends ApiStaffController
{
    public function index(Request $request)
    {
        return response()->json(parent::index($request));
    }

    public function create(StaffRequest $request)
    {
        return response()->json(parent::create($request));
    }

    public function update(StaffRequest $request, $userId)
    {
        return response()->json(parent::update($request, $userId));
    }

    public function delete($userId, $vendorId)
    {
        parent::delete($userId, $vendorId);

        return $this->successJsonRes([
            'user_id' => $userId,
            'vendor_id' => $vendorId,
        ], __('api.staff_deleted'));
    }
}
