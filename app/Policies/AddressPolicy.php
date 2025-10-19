<?php

namespace App\Policies;

use App\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_addresses');
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Address $address): bool
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_addresses');
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Model $user): bool
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('create_addresses');
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
    public function update(Model $user, Address $address): bool
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_addresses');
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Address $address): bool
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_addresses');
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, Address $address): bool
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_addresses');
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Model $user, Address $address): bool
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_addresses');
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
