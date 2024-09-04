<?php

namespace App\Services\V3_1;

use App\Models\User;
use App\Services\UserService as BaseUserService;

class UserService extends BaseUserService
{
    /**
     * Create new user with relations.
     *
     * @param  array  $data
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
        return [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'profile' => [
                'name' => $data['name'],
                'gender' => $data['gender'] ?? null,
            ],
        ];
    }
}
