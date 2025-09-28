<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Controllers\Api\BaseApiController;

class RegistrationInterestController extends BaseApiController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function registerInterestForm()
    {
        return view('frontend.register-user', $this->data());
    }

    public function registerInterest(RegisterUserRequest $request)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data) {
                $user_data = $data;

                $user_data += [
                    'name'          => $user_data['firstname'] . ' ' . $user_data['lastname'],
                    'profile'       => [
                        'firstname'     => $user_data['firstname'] ?? null,
                        'lastname'      => $user_data['lastname'] ?? null,
                    ],
                ];

                $isAffiliate = isset($data['is_affiliate']) ? true : false;

                $user = $this->userService->create(data: $user_data, isAffiliate: $isAffiliate);

                Auth::login($user);

                event(new Registered($user));
            });

            Auth::logout();

            return back()->with('success', __('Your account has been successfully registered. Please log in from the application and activate the account.'));
        } catch (Exception $exception) {
            dd($exception->getMessage());
            $this->logReq($exception->getMessage());

            return back()->with('error', 'something went wrong, please contact support');
        }
    }
}
