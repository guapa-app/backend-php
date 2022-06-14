<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Helpers\Common;
use App\Models\User;

/**
 * User Repository
 */
class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
	/**
	 * Items per page for pagination
	 * @var integer
	 */
	public $perPage = 10;

	/**
	 * Construct an instance of the repo
	 * 
	 * @param \App\Models\User $model
	 */
	public function __construct(User $model)
	{
		parent::__construct($model);
	}

	/**
	 * Get user by phone
	 * 
	 * @param  string $phone
	 * @return \App\Models\User|null
	 */
	public function getByPhone(string $phone) : ?User
	{
		$variations = Common::getPhoneVariations($phone);
		return $this->model->whereIn('phone', $variations)->first();
	}

	/**
	 * Get user by username
	 * 
	 * @param  string $username
	 * @return \App\Models\User|null
	 */
	public function getByUsername(string $username) : ?User
	{
        $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);
		return ! $isEmail ?
			$this->getByPhone($username) :
			$this->model->where('email', $username)->first();
	}
}
