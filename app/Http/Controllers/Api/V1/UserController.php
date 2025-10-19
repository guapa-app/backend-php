<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UserController extends ApiUserController
{
    public function single(Request $request, $id)
    {
        return response()->json(parent::single($request, $id));
    }

    public function update(UserRequest $request, $id = 0)
    {
        return response()->json(parent::update($request, $id));
    }
}
