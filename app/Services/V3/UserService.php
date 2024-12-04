<?php

namespace App\Services\V3;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\Exceptions\PhoneNotVerifiedException;
use App\Services\UserService as BaseUserService;

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

    public function update($id, array $data): User
    {
        // Create user
        $user = $this->userRepository->update($id, $data);

        // Update profile
        if (isset($data['profile'])) {
            $this->updateProfile($user, (array)$data['profile']);
        }

        return $user;
    }

    public function handleUserData(mixed $data): array
    {
        $result = [];

        if (isset($data['name'])) {
            // Split the string by spaces
            $nameParts = explode(' ', $data['name']);
            // Assign the first and last name
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $result['name'] = $data['name'];
            $result['profile'] = [
                'firstname'     => $firstName,
                'lastname'      => $lastName,
            ];
        }

        if (!in_array($data['gender'], UserProfile::GENDER)) {
            $data['gender'] = null;
        }

        if (isset($data['gender'])) {
            $result['profile']['gender'] = $data['gender'];
        }

        if ($photo = $data['photo'] ?? $data['logo'] ?? null) {
            $result['profile']['photo'] = $photo;
        }

        if (isset($data['email'])) {
            $result['email'] = $data['email'];
        }

        if (isset($data['phone'])) {
            $result['phone'] = $data['phone'];
        }

        return $result;
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
