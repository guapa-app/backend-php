<?php

namespace App\Policies;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class SettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_settings');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Setting $setting): bool
    {
        try {
            return $user->hasPermissionTo('view_settings');
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
            return $user->hasPermissionTo('create_settings');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Setting $setting): bool
    {
        try {
            return $user->hasPermissionTo('update_settings');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Setting $setting): bool
    {
        try {
            return $user->hasPermissionTo('delete_settings');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
