<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseAdminController
{
    private $userService;
    private $userRepository;

    public function __construct(UserService $userService, UserRepositoryInterface $userRepository)
    {
        parent::__construct();

        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $users = $this->userRepository->all($request);

        return response()->json($users);
    }

    public function single($id = 0)
    {
        $user = $this->userRepository->getOneWithRelations($id);

        return response()->json($user);
    }

    public function create(UserRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->create($data);

        return response()->json($user);
    }

    public function update(UserRequest $request, $id = 0)
    {
        $user = $this->userService->update($id, $request->validated());

        return response()->json($user);
    }

    public function delete($id = 0)
    {
        $ids = $this->userRepository->delete($id);

        return response()->json($ids);
    }
}
