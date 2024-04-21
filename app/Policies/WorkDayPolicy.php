<?php

namespace App\Policies;

use App\Models\WorkDay;
use Illuminate\Database\Eloquent\Model;

class WorkDayPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_work_days');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, WorkDay $workDay): bool
    {
        try {
            return $user->hasPermissionTo('view_work_days');
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
            return $user->hasPermissionTo('create_work_days');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, WorkDay $workDay): bool
    {
        try {
            return $user->hasPermissionTo('update_work_days');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, WorkDay $workDay): bool
    {
        try {
            return $user->hasPermissionTo('delete_work_days');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
