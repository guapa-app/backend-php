<?php

namespace App\Policies;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_appointments');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Appointment $appointment): bool
    {
        try {
            return $user->hasPermissionTo('view_appointments');
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
            return $user->hasPermissionTo('create_appointments');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Appointment $appointment): bool
    {
        try {
            return $user->hasPermissionTo('update_appointments');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Appointment $appointment): bool
    {
        try {
            return $user->hasPermissionTo('delete_appointments');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
