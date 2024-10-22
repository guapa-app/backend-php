<?php

namespace App\Http\Controllers\Api\Vendor\V3_1;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Exceptions\ApiException;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\Vendor\RegisterRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Http\Resources\Vendor\V3_1\LoginResource;
use App\Http\Resources\Vendor\V3_1\VendorProfileResource;
use App\Models\Setting;
use App\Services\AuthService;
use App\Services\SMSService;
use App\Services\V3\UserService;
use App\Services\V3_1\VendorService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseApiController
{
    private $smsService;
    private $vendorService;
    protected $authService;
    protected $userService;
    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthService $authService,
        UserService $userService,
        SMSService $smsService,
        VendorService $vendorService
    ) {
        parent::__construct();

        $this->authService = $authService;
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->smsService = $smsService;
        $this->vendorService = $vendorService;
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
        DB::transaction(function () use ($data) {
            // handle user date to manage profile.
            $user_data = $this->userService->handleUserData($data);

            // create user
            $user = $this->userService->create($user_data);

            // send otp to the user to verify account.
            if (!Setting::checkTestingMode()) {
                $this->smsService->sendOtp($data['phone']);
            }
        });

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

        $this->userService->checkIfUserDeleted($user->status);

        $user->loadMissing('vendor');

        if (Setting::checkTestingMode() || !str_contains($data['phone'], '966123456789')) {
            $token = $user->createToken('Temp Personal Token', ['*']);
            $tokenData = [
                'access_token' => $token->accessToken,
                'refresh_token' => null, // In testing mode, we don't have a refresh token
            ];
        } else {
            $requestPayload = [
                'grant_type' => 'otp_verify',
                'phone_number' => $data['phone'],
                'otp' => $data['otp'],
                'scope' => '*',
            ];

            $tokenData = $this->authService->authenticate($requestPayload);

            if (!$tokenData) {
                return $this->errorJsonRes([
                    'otp' => [__('api.incorrect_otp')],
                ], __('api.incorrect_otp'), 406);
            }
        }

        $user = $this->prepareUserResponse($user, $tokenData);

        return LoginResource::make($user)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function userVendor()
    {
        $vendor = $this->user->vendor;

        $vendor->loadMissing('logo', 'staff', 'specialties', 'workDays', 'appointments', 'addresses', 'socialMedia', 'socialMedia.icon');

        $this->userService->checkIfUserDeleted($this->user->status);

        $this->user->append('user_vendors_ids');

        return VendorProfileResource::make($vendor)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
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

    public function user()
    {
        $this->user->loadProfileFields();

        $this->userService->checkIfUserDeleted($this->user->status);

        $this->user->append('user_vendors_ids');

        return $this->user;
    }

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

    public function logout(Request $request)
    {
        $this->authService->logout($this->user);

        return $this->successJsonRes([], __('api.success'));
    }

    public function deleteAccount(Request $request)
    {
        $this->userService->deleteAccount($this->user->getKey());

        $this->authService->logout($this->user);

        return $this->successJsonRes([], __('api.account_deleted'));
    }

    public function checkIfPhoneExist(Request $request)
    {
        $data = $this->validate($request, [
            'phone' => 'required|string|max:40',
        ]);

        $user = $this->userRepository->getByPhone($data['phone']);
        if ($user == null) {
            return $this->successJsonRes([
                'is_phone_exists' => false,
            ], __('api.phone_doesnt_exist'), 200);
        } else {
            return $this->successJsonRes([
                'is_phone_exists' => true,
            ], __('api.phone_exist'), 200);
        }
    }

}
