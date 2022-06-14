<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Contracts\Repositories\AdminRepositoryInterface;

class AuthController extends BaseAdminController
{
    private $authService;
    private $adminRepository;

    public function __construct(AuthService $authService,
        AdminRepositoryInterface $adminRepository)
    {
        parent::__construct();
        
        // Initialize service
        $this->authService = $authService;
        $this->adminRepository = $adminRepository;
        // Setup default admin account if not exists
        $admin = $this->authService->setupAdminAccount();
    }

    /**
     * Log the user in to the api using email and password
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate request
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Authenticate admin
        $token = $this->authService->authenticate([
            'grant_type' => 'password',
            'username' => $request->get('email'),
            'password' => $request->get('password'),
            'client_id' => config('cosmo.admin_client_id'),
            'client_secret' => config('cosmo.admin_client_secret'),
        ]);

        if ($token == null) {
            return response()->json([
                'message' => "Wrong email or password"
            ], 401);
        }

        return response()->json([
            'token' => $token,
            'data' => $this->adminRepository->getOne(0, [
                'email' => $request->get('email'),
            ]),
        ]);
    }

    /**
     * Log user out from api
     * @param  \Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json([], 200);
    }
}
