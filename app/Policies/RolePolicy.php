<?php

namespace App\Policies;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_roles');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Role $role): bool
    {
        try {
            return $user->hasPermissionTo('view_roles');
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
            return $user->hasPermissionTo('create_roles');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Role $role): bool
    {
        try {
            return $user->hasPermissionTo('update_roles');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Role $role): bool
    {
        try {
            return $user->hasPermissionTo('delete_roles');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
