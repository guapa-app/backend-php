<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

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


    public function successJsonRes(array $data= [], string $message = "", $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => $data
        ], $status);
    }

    public function errorJsonRes(array $errors= [], string $message = "", $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            "success" => false,
            "message" => $message,
            "errors" => $errors
        ], $status);

    }

    public function logReq($message = "")
    {
        Log::alert("*** " .
            \request()->method() .
            " >-> " . \request()->decodedPath() .
            " >-> " . \request()->route()->getName() .
            " *** \n",
            [
                "Request Data >-> " => \request()->all(),
                "\nMessage >-> " => $message,
            ]);
    }
}
