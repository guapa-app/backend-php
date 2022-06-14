<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Services\UserService;
use App\Contracts\Repositories\UserRepositoryInterface;

/**
 * @group Authentication
 */
class AuthController extends BaseApiController
{
    private $authService;
    private $userService;
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository,
        AuthService $authService, UserService $userService)
    {
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
     * @param  \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $token = null;

        if (isset($data['firebase_jwt_token']) || isset($data['otp'])) {
            $grantType = isset($data['firebase_jwt_token']) ? 'firebase_phone' : 'sinch_verify';
            $isFirebase = $grantType === 'firebase_phone';
            $requestPayload = [
                'grant_type' => $grantType,
                'phone_number' => $data['phone'],
                'scope' => '*',
            ];

            if ($isFirebase) {
                $requestPayload['jwt_token'] = $data['firebase_jwt_token'];
            } else {
                $requestPayload['otp'] = $data['otp'];
            }

            $token = $this->authService->authenticate($requestPayload);
        }

        $userData = [
            'name' => $data['name'] ?? $data['firstname'] . ' ' . $data['lastname'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'profile' => [
                'firstname' => $data['firstname'] ?? null,
                'lastname' => $data['lastname'] ?? null,
            ],
        ];

        if ($token != null) {
            $user = $this->userRepository->getByPhone($data['phone']);
            $user = $this->userService->update($user, $userData);
        } else {
            $user = $this->userService->create($userData);
        }

        $user->password = \Hash::make($data['password']);
        $user->save();

        if ($token == null) {
            $token = $this->authService->authenticate([
                'grant_type' => 'password',
                'username' => $data['email'] ?? null,
                'password' => $data['password'],
                'scope' => '*',
            ]);
        }

        event(new Registered($user));

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	$this->validate($request, [
    		'username' => 'required|string',
            'password' => 'required|max:100',
    	]);

//        logger("It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters - " . $request->get('username') . " " . $request->get('password'));

        $token = $this->authService->authenticate([
            'grant_type' => 'password',
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'guard' => 'api',
            'scope' => '*',
        ]);

        if ($token == null) {
            return response()->json([
                'message' => __('api.invalid_credentials'),
            ], 401);
        }

        $username = $request->get('username');
        $user = $this->userRepository->getByUsername($username);

        if ($user->phone_verified_at == null && strpos($username, '@') === false) {
            // User is trying to login with phone number, before verification
            return response()->json([
                'message' => __('api.phone_not_verified'),
                'phone_verified' => false,
            ], 401);
        }

        $user->loadProfileFields();
        $user->append('user_vendors_ids');

        return response()->json([
        	'token' => $token,
        	'user' => $user,
        ]);
    }

    /**
     * Get logged in user
     *
     * @authenticated
     *
     * @responseFile 200 responses/auth/current-user.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $this->user->loadProfileFields();
        $this->user->append('user_vendors_ids');
        return response()->json($this->user);
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
        try {
            return $this->authService->authenticate([
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->get('refresh_token'),
                'scope' => '*',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('api.invalid_refresh_token'),
            ], 401);
        }
    }

    /**
     * Logout
     *
     * @responseFile 200 responses/auth/logout.json
     * @responseFile 401 scenario="Unauthorized" responses/errors/401.json
     *
     * @param  \Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->authService->logout($this->user);
        return response()->json([], 200);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $data = $this->validate($request, [
            'phone' => 'required|string|max:30',
            'firebase_jwt_token' => 'required_without:otp|string|max:2000',
            'otp' => 'required_without:firebase_jwt_token|string|max:10',
            'for_reset_password' => 'sometimes|required',
        ]);

        $user = $this->userRepository->getByPhone($data['phone']);

        if ( ! $user) {
            abort(404, 'Phone number doesn\'t exist.');
        }

        $grantType = isset($data['otp']) ? 'sinch_verify' : 'firebase_phone';

        $tokenPayload = [
            'grant_type' => $grantType,
            'phone_number' => $data['phone'],
            'scope' => '*',
        ];

        if ($grantType === 'sinch_verify') {
            $tokenPayload['otp'] = $data['otp'];
        } else {
            $tokenPayload['jwt_token'] = $data['firebase_jwt_token'];
        }

        $token = $this->authService->authenticate($tokenPayload);

        if ($token == null) {
            return response()->json([
                'message' => __('api.phone_verification_failed'),
            ], 401);
        }

        $user->update(['phone_verified_at' => now()->toDateTimeString()]);

        $responseBody = [
            'user' => $user,
            'token' => $token,
        ];

        if (isset($data['for_reset_password'])) {
            $resetToken = Str::random(64);

            \DB::table(config('auth.passwords.users.table'))->insert([
                'email' => $user->email,
                'token' => $resetToken,
                'created_at' => now(),
            ]);

            $responseBody['reset_token'] = $resetToken;
        }

        return response()->json($responseBody);
    }

    /**
     * Send otp
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSinchOtp(Request $request)
    {
        $data = $this->validate($request, [
            'phone' => 'required|string|max:40',
        ]);

        $this->authService->sendSinchOtp($data['phone']);

        return response()->json([
            'message' => __('api.otp_sent'),
        ]);
    }

    /**
     * Verify otp
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     * @bodyParam otp string required Otp from sms
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifySinchOtp(Request $request)
    {
        $data = $this->validate($request, [
            'phone' => 'required|string|max:40',
            'otp' => 'required|string|max:10',
        ]);

        $bool = $this->authService->verifySinchOtp($data['phone'], $data['otp']);

        return response()->json([
            'message' => $bool ? __('api.correct_otp') : __('api.incorrect_otp'),
        ], $bool ? 200 : 406);
    }

    /**
     * Check if phone or email exists.
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkIfPhoneExist(Request $request)
    {
        $data = $this->validate($request, [
            'phone' => 'required|string|max:40',
        ]);

        $user = $this->userRepository->getByPhone($data['phone']);

        return response()->json([
            'message' => $user == null ?
                __('api.phone_doesnt_exist')
                : __('api.phone_exist'),
        ], $user == null ? 422 : 200);
    }
}
