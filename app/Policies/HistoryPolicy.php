<?php

namespace App\Policies;

use App\Models\History;
use Illuminate\Database\Eloquent\Model;

class HistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_histories');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, History $history): bool
    {
        try {
            return $user->hasPermissionTo('view_histories');
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
            return $user->hasPermissionTo('create_histories');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, History $history): bool
    {
        try {
            return $user->hasPermissionTo('update_histories');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, History $history): bool
    {
        try {
            return $user->hasPermissionTo('delete_histories');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
