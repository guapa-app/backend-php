<?php

namespace App\Http\Controllers;

use App\Mail\ErrorAlarmMail;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use function request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Current user instance.
     * @var User|Admin|null
     */
    protected $user;

    public function isAdmin()
    {
        return $this->user && $this->user->isAdmin();
    }

    public function successJsonRes(array $data = [], string $message = '', $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => (object) $data,
        ], $status);
    }

    public function errorJsonRes(array $errors = [], string $message = '', $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public function logReq($message = '', $data = null)
    {
        Log::alert(
            '***' .
                "\nMessage >-> $message" .
                "\nReq method >-> " . request()->method() .
                "\nPath >-> " . request()->decodedPath() .
                "\nRoute name >-> " . request()->route()->getName() .
                "\n***",
            [
                "\nRequest Data >-> " => request()->all(),
                "\nData >-> " => $data,
            ]
        );
    }

    public function sendAlarmMail($message = '', $exception = []): void
    {
        Mail::to(config('app.support_email'))->send(new ErrorAlarmMail($message, $exception));
    }
}
