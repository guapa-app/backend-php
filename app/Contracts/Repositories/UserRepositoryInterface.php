<?php

namespace App\Contracts\Repositories;

use App\Models\User;

/**
 * User Repository Interface
 */
interface UserRepositoryInterface
{
	/**
	 * Get user by phone
	 * @param  string $phone
	 * @return \App\Models\User|null
	 */
	public function getByPhone(string $phone) : ?User;

	/**
	 * Get user by username
	 * 
	 * @param  string $username
	 * @return \App\Models\User|null
	 */
	public function getByUsername(string $username) : ?User;
}
