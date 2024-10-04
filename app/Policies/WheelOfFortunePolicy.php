<?php

namespace App\Policies;

use App\Models\WalletChargingPackage;
use Illuminate\Database\Eloquent\Model;

class WheelOfFortunePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_wheel_of_fortunes');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, WalletChargingPackage $walletChargingPackage): bool
    {
        try {
            return $user->hasPermissionTo('view_wheel_of_fortunes');
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
            return $user->hasPermissionTo('create_wheel_of_fortunes');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, WalletChargingPackage $walletChargingPackage): bool
    {
        try {
            return $user->hasPermissionTo('update_wheel_of_fortunes');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, WalletChargingPackage $walletChargingPackage): bool
    {
        try {
            return $user->hasPermissionTo('delete_wheel_of_fortunes');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
