<?php

namespace App\Policies;

use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_orders');
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
    public function view(Model $user, Order $order)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('view_orders');
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
                return $user->hasPermissionTo('create_orders');
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
    public function update(Model $user, Order $order)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_orders');
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
    public function delete(Model $user, Order $order)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_orders');
            } else {
                return $order->vendor && $order->vendor->hasUser($user);
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Model $user, Order $order)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('update_orders');
            } else {
                return $order->vendor && $order->vendor->hasUser($user);
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Model $user, Order $order)
    {
        try {
            if ($user->isAdmin()) {
                return $user->hasPermissionTo('delete_orders');
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
