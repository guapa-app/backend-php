<?php

namespace App\Policies;

use App\Models\SupportMessageType;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class SupportMessageTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_support_message_types');
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
    public function view(Model $user, SupportMessageType $support_message_type)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_support_message_types');
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
    public function create(Model $user)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('create_support_message_types');
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
    public function update(Model $user, SupportMessageType $support_message_type)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_support_message_types');
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
    public function delete(Model $user, SupportMessageType $support_message_type)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_support_message_types');
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
    public function restore(Model $user, SupportMessageType $support_message_type)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_support_message_types');
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
    public function forceDelete(Model $user, SupportMessageType $support_message_type)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_support_message_types');
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
