<?php

namespace App\Providers;

use App\Nova\Metrics\CityTrend;
use App\Nova\Metrics\CommentTrend;
use App\Nova\Metrics\OfferTrend;
use App\Nova\Metrics\OrderTrend;
use App\Nova\Metrics\PostTrend;
use App\Nova\Metrics\ProductTrend;
use App\Nova\Metrics\ReviewTrend;
use App\Nova\Metrics\SupportMessageTrend;
use App\Nova\Metrics\TaxonomyTrend;
use App\Nova\Metrics\UserTrend;
use App\Nova\Metrics\VendorTrend;
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
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            CityTrend::make()->width('1/2'),
            CommentTrend::make()->width('1/2'),
            OfferTrend::make()->width('1/2'),
            OrderTrend::make()->width('1/2'),
            PostTrend::make()->width('1/2'),
            ProductTrend::make()->width('1/2'),
            ReviewTrend::make()->width('1/2'),
            SupportMessageTrend::make()->width('1/2'),
            TaxonomyTrend::make()->width('1/2'),
            UserTrend::make()->width('1/2'),
            VendorTrend::make()->width('1/2'),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
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
}
