<?php

namespace App\Policies;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;

class ReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_reviews');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Review $review): bool
    {
        try {
            return $user->hasPermissionTo('view_reviews');
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
            return $user->hasPermissionTo('create_reviews');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Review $review): bool
    {
        try {
            return $user->hasPermissionTo('update_reviews');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Review $review): bool
    {
        try {
            return $user->hasPermissionTo('delete_reviews');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
