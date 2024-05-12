<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Requests\PhoneRequest;
use App\Http\Requests\VerifyPhoneRequest;
use App\Models\Setting;
use App\Services\AuthService;
use App\Services\SMSService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exceptions\ApiException;

class OTPController extends BaseApiController
{
    private $authService;
    private $smsService;
    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        SMSService $smsService,
        AuthService $authService,
    ) {
        parent::__construct();

        $this->smsService = $smsService;
        $this->authService = $authService;
        $this->userRepository = $userRepository;
    }

    /**
     * Verify phone.
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
            abort(404, __('api.phone_doesnt_exist'));
        }
         
        if (Setting::checkTestingMode()) {
            $personalAccessToken = $user->createToken('Temp Personal Token', ['*']);
            $token['access_token'] = $personalAccessToken->accessToken;
        } else {
            $token['access_token'] = $user->createToken('Temp Personal Token', ['*'])->accessToken;
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
     * Send otp.
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     *
     * @param Request $request
     */
    public function sendOtp(PhoneRequest $request)
    {
        $data = $request->validated();

        return $this->smsService->sendOtp($data['phone']);
    }

    /**
     * Verify otp.
     *
     * @unauthenticated
     *
     * @bodyParam phone string required Phone number
     * @bodyParam otp string required Otp from sms
     *
     * @param VerifyPhoneRequest $request
     */
    public function verifyOtp(VerifyPhoneRequest $request)
    {
        $data = $request->validated();

        return $this->smsService->verifyOtp($data['phone'], $data['otp']);
    }

    private function checkUserCredentials($token)
    {
        if ($token == null) {
            throw new ApiException(__('api.invalid_credentials'), 401);
        }
    }
}
