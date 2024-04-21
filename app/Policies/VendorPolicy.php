<?php

namespace App\Policies;

use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class VendorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user)
    {
        try {
            return $user->hasPermissionTo('view_vendors');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Vendor $vendor)
    {
        try {
            return $user->hasPermissionTo('view_vendors');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('create_vendors');
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Vendor $vendor)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_vendors');
            } else {
                return $user->hasRole('manager') && $vendor->hasManager($user);
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Vendor $vendor)
    {
        try {
            return $user->hasPermissionTo('delete_vendors');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
