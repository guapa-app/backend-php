<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Contracts\Repositories\AdminRepositoryInterface;
use App\Models\Admin;

/**
 * Admin Repository
 */
class AdminRepository extends EloquentRepository implements AdminRepositoryInterface
{
	/**
	 * Construct an instance of the repo
	 * @param \App\Models\Admin $model
	 */
	public function __construct(Admin $model)
	{
		parent::__construct($model);
	}

	/**
	 * Create new model and persist in db
	 * @param  array  $data
	 * @return Illuminate\Database\Eloquent\Model
	 */
	public function create(array $data) : Model
	{
        $data['password'] = Hash::make($data['password']);
		$admin = parent::create($data);
        if (isset($data['role'])) {
            $admin->assignRole(strtolower($data['role']));
        }
        
        return $admin;
	}

	/**
	 * Update admin
	 * @param  mixed  $model
	 * @param  array  $data
	 * @param  array  $where
	 * @return Illuminate\Database\Eloquent\Model
	 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function update($admin, array $data, $where = []) : Model
	{
		$admin = $this->isModel($admin) ? $admin : $this->getOneOrFail($admin, $where);
		// Prevent changing the role of the super admin, even by the super admin him self
        if (($admin->hasRole('superadmin') && isset($data['role'])) ||
    		! $this->user->hasRole('superadmin') || strtolower($data['role']) === 'superadmin') {
            unset($data['role']);
        } elseif (isset($data['role'])) {
            // Update the roles for the updated admin account
            $admin->syncRoles([strtolower($data['role'])]);
        }

        // Check if we should update password
        $passwordParams = $this->only($data, ['adminpassword', 'password', 'password_confirmation']);
        // In the validation we made sure that the 3 parameters are provided together
        // Or validation error will be returned
        if (count($passwordParams) === 3) {
            $this->validate_password($data, $this->user->password);
            $data['password'] = Hash::make($data['password']);
        }

		$admin->update($data);
		return $admin;
	}

	/**
	 * Validate password data
	 * @param  array $data
	 * @param  string $admin_dbpassword
	 * @return boolean
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function validate_password($data, $adminDbPassword) : bool
    {
        if ( ! Hash::check($data['adminpassword'], $adminDbPassword)) {
            $error = 'Your admin password is incorrect';
        } elseif (mb_strlen($data['password']) < 6) {
           	$error = 'The password must be at least six characters in length';
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $error = 'Passwords don\'t match';
        }
        
        if (isset($error)) {
        	throw ValidationException::withMessages([
        		'password' => [$error],
        	]);
        }

        return true;
    }

    /**
	 * Delete by ids
	 * @param  int|json $id
	 * @return void
	 */
	public function delete($id, $where = []): array
	{
        if ( ! $this->user || ! $this->user->hasRole('superadmin')) {
            abort(403, 'Only Super admin can delete admin accounts');
        }

		if (is_numeric($id) && $id > 0) {
            $model = $this->getOne($id, $where);
            if ($model->hasRole('superadmin')) {
                abort(403, 'You can\'t delete super admin account');
            }

            $model->delete();
            return [$id];
        } else {            
            try {
                $ids = (array) json_decode(urldecode($id));
                $this->model->whereIn('id', $ids)
                    ->role(['admin', 'moderator'], 'admin') // roles, guard
                	->where($where)->delete();
                return $ids;
            } catch(\Exception $e) {
                // Not valid json
                // No big deal
                return [];
            }
        }
	}
}
