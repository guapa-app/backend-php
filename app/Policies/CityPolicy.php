<?php

namespace App\Policies;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;

class CityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_cities');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, City $city): bool
    {
        try {
            return $user->hasPermissionTo('view_cities');
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
            return $user->hasPermissionTo('create_cities');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, City $city): bool
    {
        try {
            return $user->hasPermissionTo('update_cities');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, City $city): bool
    {
        try {
            return $user->hasPermissionTo('delete_cities');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
