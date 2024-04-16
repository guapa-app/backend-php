<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'user'       => 'App\Models\User',
            'profile'    => 'App\Models\UserProfile',
            'admin'      => 'App\Models\Admin',
            'media'      => 'App\Models\Media',
            'device'     => 'App\Models\Device',
            'vendor'     => 'App\Models\Vendor',
            'doctor'     => 'App\Models\Doctor',
            'product'    => 'App\Models\Product',
            'offer'      => 'App\Models\Offer',
            'post'       => 'App\Models\Post',
            'comment'    => 'App\Models\Comment',
        ]);

        Passport::tokensExpireIn(Carbon::now()->addDays(30));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(365));

        $this->app['request']->server->set('HTTPS', $this->app->environment() != 'local');
    }
}
