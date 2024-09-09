<?php

namespace App\Policies;

use App\Models\SocialMedia;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class SocialMediaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_social_media');
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
    public function view(Model $user, SocialMedia $social_media)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_social_media');
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
                return $user->hasPermissionTo('create_social_media');
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
    public function update(Model $user, SocialMedia $social_media)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_social_media');
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
    public function delete(Model $user, SocialMedia $social_media)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_social_media');
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
    public function restore(Model $user, SocialMedia $social_media)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_social_media');
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
    public function forceDelete(Model $user, SocialMedia $social_media)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_social_media');
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
