<?php

namespace App\Policies;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class OrderItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_order_items');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, OrderItem $orderItem): bool
    {
        try {
            return $user->hasPermissionTo('view_order_items');
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
            return $user->hasPermissionTo('create_order_items');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, OrderItem $orderItem): bool
    {
        try {
            return $user->hasPermissionTo('update_order_items');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, OrderItem $orderItem): bool
    {
        try {
            return $user->hasPermissionTo('delete_order_items');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
