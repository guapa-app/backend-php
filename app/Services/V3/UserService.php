<?php

namespace App\Services\V3;

use App\Exceptions\ApiException;
use App\Exceptions\PhoneNotVerifiedException;
use App\Models\User;
use App\Services\UserService as BaseUserService;
use Illuminate\Http\Request;

class UserService extends BaseUserService
{
    /**
     * Create new user with relations.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        // Create user
        $user = $this->userRepository->create($data);

        // Assign patient role
        $user->assignRole('patient');

        // Update profile
        if (isset($data['profile'])) {
            $this->updateProfile($user, (array) $data['profile']);
        }

        return $user;
    }

    public function handleUserData(mixed $data): array
    {
        // Split the string by spaces
        $nameParts = explode(' ', $data['name']);
        // Assign the first and last name
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        return [
            'name'          => $data['name'],
            'email'         => $data['email'] ?? null,
            'phone'         => $data['phone'],
            'profile'       => [
                'firstname'     => $firstName,
                'lastname'      => $lastName,
                'gender'        => $data['gender'] ?? null,
                'photo'         => $data['photo'] ?? $data['logo'] ?? null,
            ],
        ];
    }

    public function checkUserCredentials($token)
    {
        if ($token == null) {
            throw new ApiException(__('api.invalid_credentials'), 401);
        }
    }

    public function checkUserVerified($phone_verified_at, $username)
    {
        if ($phone_verified_at == null && strpos($username, '@') === false) {
            throw new PhoneNotVerifiedException();
        }
    }

    public function checkIfUserDeleted($status)
    {
        if ($status == User::STATUS_DELETED) {
            throw new ApiException(__('api.account_deleted'), 401);
        }
    }

    public function user($user)
    {
        $user->loadProfileFields();

        $this->checkIfUserDeleted($user->status);

        $user->append('user_vendors_ids');

        return $user;
    }

    public function checkIfPhoneExist(Request $request)
    {
        $data = $this->validate($request, [
            'phone' => 'required|string|max:40',
        ]);

        return $this->userRepository->getByPhone($data['phone']);
    }
}
