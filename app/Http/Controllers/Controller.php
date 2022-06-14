<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Current user instance
     * @var \App\Models\User|\App\Models\Admin|null
     */
    protected $user;

    public function isAdmin()
    {
    	return $this->user && $this->user->isAdmin();
    }

    /**
     * Add new device
     *
     * @authenticated
     * 
     * @param \App\Http\Requests\DeviceRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addDevice(DeviceRequest $request, UserService $service) : JsonResponse
    {
        $data = $request->validated();
        $device = $service->addDevice($this->user, $data);
        return response()->json($device);
    }
}
