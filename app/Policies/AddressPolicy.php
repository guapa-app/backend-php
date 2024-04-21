<?php

namespace App\Policies;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class AddressPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_addresses');
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
            return $user->hasPermissionTo('view_addresses');
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
            return $user->hasPermissionTo('create_addresses');
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
            return $user->hasPermissionTo('update_addresses');
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
            return $user->hasPermissionTo('delete_addresses');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
