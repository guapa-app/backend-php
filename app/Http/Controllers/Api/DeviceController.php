<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use App\Services\UserService;

class DeviceController extends BaseApiController
{
    /**
     * Add new device.
     *
     * @authenticated
     *
     * @param DeviceRequest $request
     * @param UserService $service
     * @return Device
     */
    public function addDevice(DeviceRequest $request, UserService $service)
    {
        $data = $request->validated();

        return $service->addDevice($this->user, $data);
    }
}
