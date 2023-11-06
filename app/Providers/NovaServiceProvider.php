<?php

namespace App\Providers;

use App\Nova\Dashboards\Main;
use App\Nova\Resources\Admin;
use App\Nova\Resources\Appointment;
use App\Nova\Resources\Invoice;
use App\Nova\Resources\Order;
use App\Nova\Resources\OrderItem;
use App\Nova\Resources\Product;
use App\Nova\Resources\Review;
use App\Nova\Resources\User;
use App\Nova\Resources\Vendor;
use App\Nova\Resources\WorkDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \Spatie\NovaTranslatable\Translatable::defaultLocales(['en', 'ar']);
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user != null;
        });
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new Main(),
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function resources()
    {
        // list of resources that vendor can access.
        // every resource use trait NovaVendorAccess
        // and fields array have a check vendor condition (to prevent access resource from edit page).
        if (Auth::user()?->isVendor()) {
            Nova::resources([
                Invoice::class,
                Product::class,
                Order::class,
                OrderItem::class,
                Review::class,
                Vendor::class,
                Admin::class,
                WorkDay::class,
                Appointment::class,
                User::class,
            ]);
        } else {
            Nova::resourcesIn(app_path('Nova/Resources'));
        }
    }
}
