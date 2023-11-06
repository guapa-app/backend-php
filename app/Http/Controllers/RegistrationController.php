<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\RegisterVendorRequest;
use App\Services\UserService;
use App\Services\VendorService;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends BaseApiController
{
    private $userService;
    private $vendorService;

    public function __construct(UserService $userService, VendorService $vendorService)
    {
        $this->userService = $userService;
        $this->vendorService = $vendorService;
    }

    public function registerForm()
    {
        return view('register', $this->data());
    }

    public function register(RegisterVendorRequest $request)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data) {
                $user_data = $data['user'];

                $user_data += [
                    'name'          => $user_data['firstname'] . ' ' . $user_data['lastname'],
                    'profile'       => [
                        'firstname'     => $user_data['firstname'] ?? null,
                        'lastname'      => $user_data['lastname'] ?? null,
                    ],
                    'password' => Hash::make($user_data['password']),
                ];

                $user = $this->userService->create($user_data);

                Auth::login($user);

                $this->vendorService->create($data['vendor']);

                event(new Registered($user));
            });

            Auth::logout();

            return back()->with('success', __('success'));
        } catch (Exception $exception) {
            $this->logReq($exception->getMessage());

            return back()->with('error', 'something went wrong, please contact support');
        }
    }
}
