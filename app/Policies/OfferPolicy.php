<?php

namespace App\Policies;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Model;

class OfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_offers');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Offer $offer): bool
    {
        try {
            return $user->hasPermissionTo('view_offers');
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
            return $user->hasPermissionTo('create_offers');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Offer $offer): bool
    {
        try {
            return $user->hasPermissionTo('update_offers');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Offer $offer): bool
    {
        try {
            return $user->hasPermissionTo('delete_offers');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
