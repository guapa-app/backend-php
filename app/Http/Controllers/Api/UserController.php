<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Services\UserService;

/**
 * @group User profile
 */
class UserController extends BaseApiController
{
    private $userRepository;
    private $userService;
    
    public function __construct(UserRepositoryInterface $userRepository,
        UserService $userService)
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
     * @param  \App\Http\Requests\UserRequest $request
     * @param  int      $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->update($this->user, $request->validated());
        $user->loadProfileFields();
	    return response()->json($user);
    }

    /**
     * Get user by id
     *
     * @urlParam id required User id
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single(Request $request, $id)
    {
        $user = $this->userRepository->getOneOrFail($id);
        $user->loadProfileFields();
    	return response()->json($user);
    }
}
