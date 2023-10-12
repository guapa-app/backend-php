<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\ApiException;
use App\Exceptions\PhoneNotVerifiedException;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Models\Setting;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use DB;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 */
class AuthController extends BaseApiController
{
    private $authService;
    private $userService;
    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthService $authService,
        UserService $userService
    ) {
        parent::__construct();

        $this->authService = $authService;
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    /**
     * Signup
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/auth/register.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     *
     * @param RegisterRequest $request
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $token = null;

        if (isset($data['firebase_jwt_token']) || isset($data['otp'])) {
            $grantType = isset($data['firebase_jwt_token']) ? 'firebase_phone' : 'sinch_verify';
            $isFirebase = $grantType === 'firebase_phone';
            $requestPayload = [
                'grant_type'   => $grantType,
                'phone_number' => $data['phone'],
                'scope'        => '*',
            ];

            if ($isFirebase) {
                $requestPayload['jwt_token'] = $data['firebase_jwt_token'];
            } else {
                $requestPayload['otp'] = $data['otp'];
            }

            $token = $this->authService->authenticate($requestPayload);
        }

        $userData = [
            'name'          => $data['name'] ?? $data['firstname'] . ' ' . $data['lastname'],
            'email'         => $data['email'] ?? null,
            'phone'         => $data['phone'],
            'profile'       => [
                'firstname'     => $data['firstname'] ?? null,
                'lastname'      => $data['lastname'] ?? null,
                'gender'        => $data['gender'] ?? null,
            ],
        ];

        if ($token != null) {
            $user = $this->userRepository->getByPhone($data['phone']);
            $user = $this->userService->update($user, $userData);
        } else {
            $user = $this->userService->create($userData);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        if ($token == null) {
            $token = $this->authService->authenticate([
                'grant_type' => 'password',
                'username'   => $data['email'] ?? null,
                'password'   => $data['password'],
                'scope'      => '*',
            ]);
        }

        event(new Registered($user));

        return [$token, $user];
    }

    /**
     * Login
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
     * @param Request $request
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|max:100',
        ]);

        $token = $this->authService->authenticate([
            'grant_type' => 'password',
            'username'   => $request->get('username'),
            'password'   => $request->get('password'),
            'guard'      => 'api',
            'scope'      => '*',
        ]);

        // dd($token);
        $this->checkUserCredentials($token);

        $username = $request->get('username');
        $user = $this->userRepository->getByUsername($username);

        $this->checkUserVerified($user->phone_verified_at, $username);
        $this->checkIfUserDeleted($user->status);

        $user->loadProfileFields();
        $user->append('user_vendors_ids');

        return [$token, $user];
    }

    private function checkUserCredentials($token)
    {
        if ($token == null)
            throw new ApiException(__('api.invalid_credentials'), 401);
    }

    private function checkUserVerified($phone_verified_at, $username)
    {
        if ($phone_verified_at == null && strpos($username, '@') === false)
            throw new PhoneNotVerifiedException();
    }

    private function checkIfUserDeleted($status)
    {
        if ($status == User::STATUS_DELETED)
            throw new ApiException(__('api.account_deleted'), 401);
    }

    /**
     * Get logged in user
     *
     * @authenticated
     *
     * @responseFile 200 responses/auth/current-user.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     */
    public function user()
    {
        $this->user->loadProfileFields();

        $this->checkIfUserDeleted($this->user->status);

        $this->user->append('user_vendors_ids');
        return $this->user;
    }

    /**
     * Refresh access token
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
        $res =  $this->authService->authenticate([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->get('refresh_token'),
            'scope' => '*',
        ]);

        if ($res == null)
            throw new ApiException(__('api.invalid_refresh_token'), 401);

        return $res;
    }

    /**
     * Logout
     *
     * @responseFile 200 responses/auth/logout.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->authService->logout($this->user);
        return true;
    }

    /**
     * Verify phone
     *
     * @unauthenticated
     *
     * @responseFile 200 responses/auth/login.json
     * @responseFile 422 scenario="Validation errors" responses/errors/422.json
     * @responseFile 404 scenario="Phone number not found" responses/errors/404.json
     * @responseFile 401 scenario="Verification failed" responses/auth/invalid-credentials.json
     *
     * @bodyParam phone string required Phone number
     * @bodyParam firebase_jwt_token Firebase jwt token (Required if otp is absent)
     * @bodyParam otp string Sinch otp (Required if firebase jwt token is absent)
     *
     * @param Request $request
     */
    public function verify(Request $request)
    {
        $data = $this->validate($request, [
            'phone'              => 'required|string|max:30',
            'otp'                => 'required_without:firebase_jwt_token|string|max:10',
            'firebase_jwt_token' => 'required_without:otp|string|max:2000',
            'for_reset_password' => 'sometimes|required',
        ]);

        $user = $this->userRepository->getByPhone($data['phone']);

        if (!$user) {
            abort(404, 'Phone number doesn\'t exist.');
        }

        $grantType = isset($data['otp']) ? 'sinch_verify' : 'firebase_phone';

        $tokenPayload = [
            'grant_type'   => $grantType,
            'phone_number' => $data['phone'],
            'scope'        => '*',
        ];

        if ($grantType === 'sinch_verify') {
            $tokenPayload['otp'] = $data['otp'];
        } else {
            $tokenPayload['jwt_token'] = $data['firebase_jwt_token'];
        }

        if (Setting::checkTestingMode()) {
            $personalAccessToken = $user->createToken('Temp Personal Token', ['*']);
            $token['access_token'] = $personalAccessToken->accessToken;
        } else {
            $token = $this->authService->authenticate($tokenPayload);
            $this->checkUserCredentials($token);
        }

        $user->update(['phone_verified_at' => now()->toDateTimeString()]);

        $responseBody = [
            'user'  => $user,
            'token' => $token,
        ];

        if (isset($data['for_reset_password'])) {
            $resetToken = Str::random(64);

            DB::table(config('auth.passwords.users.table'))->insert([
                'email'      => $user->email ?? $user->phone,
                'token'      => $resetToken,
                'created_at' => now(),
            ]);

            $responseBody['reset_token'] = $resetToken;
        }

        return $responseBody;
    }

    /**
     * Delete Account
     *
     * @param Request $request
     */
    public function deleteAccount(Request $request)
    {
        $this->userService->deleteAccount($this->user->getKey());
        return $this->logout($request);
    }

    /**
     * Send otp
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     *
     * @param Request $request
     */
    public function sendSinchOtp(PhoneRequest $request)
    {
        $data = $request->validated();

        return $this->authService->sendSinchOtp($data['phone']);
    }

    /**
     * Verify otp
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     * @bodyParam otp string required Otp from sms
     *
     * @param VerifyPhoneRequest $request
     */
    public function verifySinchOtp(VerifyPhoneRequest $request)
    {
        $data = $request->validated();

        return $this->authService->verifySinchOtp($data['phone'], $data['otp']);
    }

    /**
     * Check if phone or email exists.
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     *
     * @param Request $request
     * @throws ValidationException
     */
    public function checkIfPhoneExist(Request $request)
    {
        $data = $this->validate($request, [
            'phone' => 'required|string|max:40',
        ]);

        return $this->userRepository->getByPhone($data['phone']);
    }
}
