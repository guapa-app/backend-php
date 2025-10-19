<?php

namespace App\Policies;

use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Model;

class TaxonomyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_taxonomies');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Taxonomy $category): bool
    {
        try {
            return $user->hasPermissionTo('view_taxonomies');
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
            return $user->hasPermissionTo('create_taxonomies');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Taxonomy $category): bool
    {
        try {
            return $user->hasPermissionTo('update_taxonomies');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Taxonomy $category): bool
    {
        try {
            return $user->hasPermissionTo('delete_taxonomies');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
