<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Vendor' => 'App\Policies\VendorPolicy',
        'App\Models\Product' => 'App\Policies\ProductPolicy',
        'App\Models\Comment' => 'App\Policies\CommentPolicy',
        'App\Models\Offer' => 'App\Policies\OfferPolicy',
        'App\Models\GiftCardBackground' => 'App\Policies\GiftCardBackgroundPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        $this->registerPolicies();
    }
}
