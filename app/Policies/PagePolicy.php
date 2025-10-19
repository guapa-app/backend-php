<?php

namespace App\Policies;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;

class PagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_pages');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Page $page): bool
    {
        try {
            return $user->hasPermissionTo('view_pages');
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
            return $user->hasPermissionTo('create_pages');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Page $page): bool
    {
        try {
            return $user->hasPermissionTo('update_pages');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Page $page): bool
    {
        try {
            return $user->hasPermissionTo('delete_pages');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
