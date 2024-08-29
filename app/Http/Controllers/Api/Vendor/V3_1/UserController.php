<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends ApiUserController
{
    public function single(Request $request, $id)
    {
        return UserResource::make(parent::single($request, $id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(UserRequest $request, $id = 0)
    {
        return UserResource::make(parent::update($request, $id))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
