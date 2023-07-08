<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @group User profile
 */
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

    /**
     * Update user profile
     *
     * @authenticated
     * @urlParam id required User id
     *
     * @param UserRequest $request
     * @param int $id
     * @return Model
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->update($this->user, $request->validated());
        $user->loadProfileFields();
        return $user;
    }

    /**
     * Get user by id
     *
     * @urlParam id required User id
     *
     * @param Request $request
     * @param int $id
     * @return Model
     */
    public function single(Request $request, $id)
    {
        $user = $this->userRepository->getOneOrFail($id);
        $user->loadProfileFields();
        return $user;
    }
}
