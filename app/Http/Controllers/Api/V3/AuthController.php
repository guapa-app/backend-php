<?php

namespace App\Http\Controllers\Api\V3;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Http\Resources\V3\UserResource;
use App\Models\Setting;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\V3\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiAuthController
{
    private $smsService;

    public function __construct(UserRepositoryInterface $userRepository, AuthService $authService, UserService $userService, SMSService $smsService)
    {
        parent::__construct($userRepository, $authService, $userService);
        $this->smsService = $smsService;
    }

    /**
     * Signup.
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/auth/register.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // handle user date to manage profile.
        $data = $this->userService->handleUserData($data);

        // create user
        $this->userService->create($data);

        // send otp to the user to verify account.
        if (!Setting::checkTestingMode()) {
            $this->smsService->sendOtp($data['phone']);
        }

        return $this->successJsonRes([
            'is_otp_sent' => true,
        ], __('api.otp_sent'));
    }

    public function verifyOtp(VerifyPhoneRequest $request)
    {
        $data = $request->validated();

        $user = $this->userRepository->getByPhone($data['phone']);

        if (!$user) {
            abort(404, __('api.phone_doesnt_exist'));
        }

        $this->checkIfUserDeleted($user->status);

        if (Setting::checkTestingMode()) {
            $token['access_token'] = $user->createToken('Temp Personal Token', ['*'])->accessToken;

            $this->prepareUserResponse($user, $token);

            return UserResource::make($user)
                ->additional([
                    'success' => true,
                    'message' => __('api.success'),
                ]);
        }

        $requestPayload = [
            'grant_type' => 'otp_verify',
            'phone_number' => $data['phone'],
            'otp' => $data['otp'],
            'scope' => '*',
        ];

        $token = $this->authService->authenticate($requestPayload);

        if ($token) {
            $user = $this->prepareUserResponse($user, $token);

            return UserResource::make($user)
                ->additional([
                    'success' => true,
                    'message' => __('api.success'),
                ]);
        } else {
            return $this->errorJsonRes([
                'otp' => [__('api.incorrect_otp')],
            ], __('api.incorrect_otp'), 406);
        }
    }

    private function prepareUserResponse($user, $token)
    {
        if ($user->phone_verified_at == null) {
            $user->update(['phone_verified_at' => now()->toDateTimeString()]);
            event(new Registered($user));
        }

        $user->loadProfileFields();
        $user->append('user_vendors_ids');
        $user->access_token = $token;

        return $user;
    }
}
