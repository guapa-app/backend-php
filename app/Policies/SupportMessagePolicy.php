<?php

namespace App\Policies;

use App\Models\SupportMessage;
use Illuminate\Database\Eloquent\Model;

class SupportMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_support_messages');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, SupportMessage $supportMessage): bool
    {
        try {
            return $user->hasPermissionTo('view_support_messages');
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
            return $user->hasPermissionTo('create_support_messages');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, SupportMessage $supportMessage): bool
    {
        try {
            return $user->hasPermissionTo('update_support_messages');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, SupportMessage $supportMessage): bool
    {
        try {
            return $user->hasPermissionTo('delete_support_messages');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
