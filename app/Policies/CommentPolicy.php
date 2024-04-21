<?php

namespace App\Policies;

use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_comments');
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
    public function view(Model $user, Comment $comment)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_comments');
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
                return $user->hasPermissionTo('create_comments');
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
    public function update(Model $user, Comment $comment)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_comments');
            } else {
                return $user->id === $comment->user_id &&
                    $user->getMorphClass() === $comment->user_type;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Comment $comment)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_comments');
            } else {
                return $user->id === $comment->user_id &&
                    $user->getMorphClass() === $comment->user_type;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
