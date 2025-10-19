<?php

namespace App\Observers;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionObserver
{
    /**
     * Handle the Permission "creating" event.
     */
    public function creating(Permission $permission): void
    {
        $permission->name = Str::snake(strtolower($permission->name));
    }

    /**
     * Handle the Permission "updating" event.
     */
    public function updating(Permission $permission): void
    {
        $permission->name = Str::snake(strtolower($permission->name));
    }
}
