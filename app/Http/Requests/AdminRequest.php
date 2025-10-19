<?php

namespace App\Http\Requests;

use App\Contracts\Repositories\AdminRepositoryInterface;
use Illuminate\Validation\Rule;

class AdminRequest extends FailedValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(AdminRepositoryInterface $adminRepository)
    {
        $id = $this->route('id');
        $user = $this->user();
        if (is_numeric($id)) {
            // Update request
            // Any admin user type can update his own account.
            $admin = $adminRepository->getOneOrFail($id);

            return ($admin->id === $user->id &&
                $user->hasRole($this->get('role'))) || $user->hasRole('superadmin');
        } else {
            // Create request
            // Only super admin can create admin accounts
            return $user->hasRole('superadmin');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('id');
        if (is_numeric($id)) {
            // Update admin main information request
            return [
                'name' => 'required|min:3',
                'email' => [
                    'required', 'email',
                    Rule::unique('admins')->ignore($this->route('id')),
                ],
                'role' => 'required|string|in:admin,moderator',
                // Any field of the following won't be present unless the user
                // Fills it in the password update tab in admin pannel
                // if any of them is present set all as required
                'adminpassword' => 'required_with:password,password_confirmation',
                'password' => 'required_with:adminpassword,password_confirmation',
                'password_confirmation' => 'required_with:adminpassword,password',
            ];
        } else {
            // Create admin request
            return [
                'name' => 'required|min:3',
                'email' => [
                    'required', 'email',
                    Rule::unique('admins'),
                ],
                'password' => 'required|min:6',
                'role' => 'required|string|in:admin,moderator',
            ];
        }
    }
}
