<?php

namespace App\Policies;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_orders');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Order $order): bool
    {
        try {
            return $user->hasPermissionTo('view_orders');
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
            return $user->hasPermissionTo('create_orders');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Order $order): bool
    {
        try {
            return $user->hasPermissionTo('update_orders');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Order $order): bool
    {
        try {
            return $user->hasPermissionTo('delete_orders');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
