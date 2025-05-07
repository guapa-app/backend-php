<?php

namespace App\Policies;

use App\Models\consultation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsultationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_consultations');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, consultation $consultation): bool
    {
        try {
            return $user->hasPermissionTo('view_consultations');
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
            return $user->hasPermissionTo('create_consultations');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, consultation $consultation): bool
    {
        try {
            return $user->hasPermissionTo('update_consultations');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, consultation $consultation): bool
    {
        try {
            return $user->hasPermissionTo('delete_consultations');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
