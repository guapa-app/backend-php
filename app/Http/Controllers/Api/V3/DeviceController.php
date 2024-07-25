<?php

namespace App\Http\Controllers\Api\V3;

use App\Http\Controllers\Api\DeviceController as ApiDeviceController;
use App\Http\Requests\DeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Services\UserService;

class DeviceController extends ApiDeviceController
{
    public function addDevice(DeviceRequest $request, UserService $service)
    {
        return DeviceResource::make(parent::addDevice($request, $service))
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }
}
