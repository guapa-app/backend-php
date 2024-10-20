<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Requests\ChangePhoneRequest;
use App\Models\User;
use App\Models\Setting;
use App\Services\SMSService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Exceptions\ApiException;
use App\Services\V3\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PhoneRequest;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\VerifyPhoneRequest;
use App\Services\V3_1\ReferralCodeService;
use App\Exceptions\PhoneNotVerifiedException;
use App\Http\Requests\V3_1\User\LoginRequest;
use App\Http\Resources\User\V3_1\UserResource;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\RegisterRequest;
use App\Contracts\Repositories\UserRepositoryInterface;
use Throwable;

class AuthController extends BaseApiController
{
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public AuthService $authService,
        public UserService $userService,
        public SMSService $smsService,
        public ReferralCodeService $referralCodeService,
    ) {
        parent::__construct();
    }

    /**
     * Signup.
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/auth/register.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @param  RegisterRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // handle user date to manage profile.
        $data = $this->userService->handleUserData($data);

        // create user
        $user = $this->userService->create($data);

        // send otp to the user to verify account.
        if (!Setting::checkTestingMode()) {
            $this->smsService->sendOtp($data['phone']);
        }

        if ($request->filled('referral_code')) {
            $this->referralCodeService->createReferralCodeUsage($user,$request->referral_code);
        }

        return $this->successJsonRes([
            'is_otp_sent' => true,
        ], __('api.otp_sent'));
    }

    /**
     * Login.
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/auth/login.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Invalid username or password" responses/auth/invalid-credentials.json
     * @responseFile 401 scenario="Phone not verified" responses/auth/login-phone-not-verified.json
     *
     * @bodyParam username string required  Email address or phone number
     * @bodyParam password string required  Password
     *
     * @param  Request  $request
     */
    public function login(LoginRequest $request)
    {
        $token = $this->authService->authenticate([
            'grant_type' => 'password',
            'phone' => $request->get('phone'),
            'password' => $request->get('password'),
            'guard' => 'api',
            'scope' => '*',
        ]);

        $this->checkUserCredentials($token);

        $username = $request->get('username');
        $user = $this->userRepository->getByUsername($username);

        $this->checkUserVerified($user->phone_verified_at, $username);
        $this->checkIfUserDeleted($user->status);

        $user->loadProfileFields();
        $user->append('user_vendors_ids');

        return [$token, $user];
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

    /**
     * Send otp.
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     *
     * @param  Request  $request
     */
    public function sendOtp(PhoneRequest $request)
    {
        try {
            if (Setting::checkTestingMode()) {
                return $this->successJsonRes([
                    'is_otp_sent' => true,
                    'otp' => 1111,
                ], __('api.otp_sent'), 200);
            }

            $data = $request->validated();

            $this->smsService->sendOtp($data['phone']);

            return $this->successJsonRes([
                'is_otp_sent' => true,
            ], __('api.otp_sent'), 200);
        } catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                throw $th;
            }
            $res = json_decode((string) $th->getResponse()?->getBody());
            if ($th instanceof \GuzzleHttp\Exception\ClientException) {
                if ($th->getCode() == 402) {
                    // 402 Not enough credit.
                } elseif ($th->getCode() == 400) {
                    // 400 Invalid phone number.
                    return $this->errorJsonRes([
                        'phone' => [__('api.invalid_phone')],
                    ], __('api.otp_not_sent'), 422);
                }
            }
            $this->logReq($res?->message);

            return $this->successJsonRes([
                'is_otp_sent' => false,
            ], __('api.contact_support'), 422);
        }
    }
    /**
     * Get logged in user.
     *
     * @authenticated
     *
     * @responseFile 200 responses/auth/current-user.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     */
    public function user()
    {
        $this->user->loadProfileFields();
        $this->user->append('user_vendors_ids');
        return UserResource::make($this->user)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    /**
     * Refresh access token.
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/auth/refresh-token.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 401 scenario="Invalid refresh token" responses/auth/invalid-credentials.json
     *
     * @bodyParam refresh_token string required Refersh token obtained during login
     */
    public function refreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required|string',
        ]);

        // Get access token from oauth server
        $res = $this->authService->authenticate([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->get('refresh_token'),
            'scope' => '*',
        ]);

        if ($res == null) {
            throw new ApiException(__('api.invalid_refresh_token'), 401);
        }

        return $res;
    }

    /**
     * Logout.
     *
     * @responseFile 200 responses/auth/logout.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     */
    public function logout()
    {
        $this->authService->logout($this->user);

        return $this->successJsonRes([], __('api.success'));
    }

    /**
     * Delete Account.
     *
     * @param  Request  $request
     */
    public function deleteAccount()
    {
        $this->userService->deleteAccount($this->user->getKey());

        return $this->logout();
    }

    public function updatePhone(ChangePhoneRequest $request)
    {
        $data = $request->validated();
        try {
            $user = $request->user();

            if ($user->phone !== $data['phone']) {
                $this->userService->updatePhoneNumber($user, $data['phone']);
            } else {
                return $this->errorJsonRes(['phone' => 'you use the same phone number'], __('api.error'), 400);
            }

            return $this->successJsonRes(['is_otp_sent' => true], __('api.otp_sent'), 200);
        } catch (Throwable $th) {

            if ($th instanceof ClientException) {
                if ($th->getCode() == 402) {
                    // 402 Not enough credit.
                } elseif ($th->getCode() == 400) {
                    // 400 Invalid phone number.
                    return $this->errorJsonRes([
                        'phone' => [__('api.invalid_phone')],
                    ], __('api.otp_not_sent'), 422);
                }
            }
            $this->logReq(json_decode($th));

            return $this->errorJsonRes([
                'is_otp_sent' => false,
            ], __('api.contact_support'), 422);
        }
    }

    private function prepareUserResponse($user, $token)
    {
        $user->update(['phone_verified_at' => now()->toDateTimeString()]);

        $user->loadProfileFields();
        $user->append('user_vendors_ids');
        $user->access_token = $token;

        event(new Registered($user));

        return $user;
    }

    private function checkUserCredentials($token)
    {
        if ($token == null) {
            throw new ApiException(__('api.invalid_credentials'), 401);
        }
    }

    private function checkUserVerified($phone_verified_at, $username)
    {
        if ($phone_verified_at == null && strpos($username, '@') === false) {
            throw new PhoneNotVerifiedException();
        }
    }

    protected function checkIfUserDeleted($status)
    {
        if ($status == User::STATUS_DELETED) {
            throw new ApiException(__('api.account_deleted'), 401);
        }
    }

}
