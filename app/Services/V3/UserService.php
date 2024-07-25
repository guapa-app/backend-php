<?php

namespace App\Services\V3;

use App\Models\User;
use App\Services\UserService as BaseUserService;
use DB;
use Hash;

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
            $this->updateProfile($user, (array)$data['profile']);
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
            ],
        ];
    }
}
