<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\DeviceController as ApiDeviceController;
use App\Http\Requests\DeviceRequest;
use App\Services\UserService;

class DeviceController extends ApiDeviceController
{
    public function addDevice(DeviceRequest $request, UserService $service)
    {
        return response()->json(parent::addDevice($request, $service));
    }
}
