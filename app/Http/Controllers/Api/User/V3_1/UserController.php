<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\UpdateUserRequest;
use App\Http\Resources\User\V3_1\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    private $userRepository;
    private $userService;

    public function __construct(UserRepositoryInterface $userRepository, UserService $userService)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }
    public function single()
    {
        $user = $this->user;
        $user->loadProfileFields();
        return UserResource::make($user)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $this->userService->update($this->user, $request->validated());
        $user->loadProfileFields();
        return UserResource::make($user)
            ->additional([
                'success' => true,
                'message' => __('api.success'),
            ]);
    }

    public function getReferralCode(Request $request)
    {
        $referralCode = $request->user()->myProfile()->getReferralCode();
        return response()->json([
            'data' => [
                'referral_code' => $referralCode
            ],
            'success' => true,
            'message' => __('api.success'),
        ]);
    }
}
