<?php

namespace App\Policies;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class InvoicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Model $user): bool
    {
        try {
            return $user->hasPermissionTo('view_invoices');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Model $user, Invoice $invoice): bool
    {
        try {
            return $user->hasPermissionTo('view_invoices');
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
            return $user->hasPermissionTo('create_invoices');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Model $user, Invoice $invoice): bool
    {
        try {
            return $user->hasPermissionTo('update_invoices');
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Model $user, Invoice $invoice): bool
    {
        try {
            return $user->hasPermissionTo('delete_invoices');
        } catch (\Throwable $th) {
            return false;
        }
    }
}
