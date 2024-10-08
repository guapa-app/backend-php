<?php

namespace App\Services\V3;

use App\Models\User;
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
}
