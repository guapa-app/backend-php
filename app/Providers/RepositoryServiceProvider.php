<?php

namespace App\Providers;

use App\Contracts\Repositories;
use App\Repositories\Eloquent;
use App\Services\Cosmo;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Repositories\AdminRepositoryInterface::class, Eloquent\AdminRepository::class);
        $this->app->bind(Repositories\AddressRepositoryInterface::class, Eloquent\AddressRepository::class);
        $this->app->bind(Repositories\CityRepositoryInterface::class, Eloquent\CityRepository::class);
        $this->app->bind(Repositories\CommentRepositoryInterface::class, Eloquent\CommentRepository::class);
        $this->app->bind(Repositories\DoctorRepositoryInterface::class, Eloquent\DoctorRepository::class);
        $this->app->bind(Repositories\EloquentRepositoryInterface::class, Eloquent\EloquentRepository::class);
        $this->app->bind(Repositories\HistoryRepositoryInterface::class, Eloquent\HistoryRepository::class);
        $this->app->bind(Repositories\OfferRepositoryInterface::class, Eloquent\OfferRepository::class);
        $this->app->bind(Repositories\OrderRepositoryInterface::class, Eloquent\OrderRepository::class);
        $this->app->bind(Repositories\PageRepositoryInterface::class, Eloquent\PageRepository::class);
        $this->app->bind(Repositories\ProductRepositoryInterface::class, Eloquent\ProductRepository::class);
        $this->app->bind(Repositories\PostRepositoryInterface::class, Eloquent\PostRepository::class);
        $this->app->bind(Repositories\ReviewRepositoryInterface::class, Eloquent\ReviewRepository::class);
        $this->app->bind(Repositories\SettingRepositoryInterface::class, Eloquent\SettingRepository::class);
        $this->app->bind(Repositories\SupportMessageRepositoryInterface::class, Eloquent\SupportMessageRepository::class);
        $this->app->bind(Repositories\TaxRepositoryInterface::class, Eloquent\TaxRepository::class);
        $this->app->bind(Repositories\UserRepositoryInterface::class, Eloquent\UserRepository::class);
        $this->app->bind(Repositories\VendorRepositoryInterface::class, Eloquent\VendorRepository::class);
        $this->app->bind(Repositories\DatabaseNotificationRepositoryInterface::class, Eloquent\DatabaseNotificationRepository::class);
        $this->app->bind(Repositories\CouponRepositoryInterface::class, Eloquent\CouponRepository::class);
        $this->app->bind(Repositories\SocialMediaRepositoryInterface::class, Eloquent\SocialMediaRepository::class);
        $this->app->bind(Repositories\InfluencerRepositoryInterface::class, Eloquent\InfluencerRepository::class);
        $this->app->bind(Repositories\MarketingCampaignRepositoryInterface::class, Eloquent\MarketingCampaignRepository::class);
        $this->app->bind(Repositories\AppointmentOfferRepositoryInterface::class, Eloquent\AppointmentRepository::class);
        $this->app->bind(Repositories\V3_1\TaxonomyRepositoryInterface::class, Eloquent\V3_1\TaxonomyRepository::class);
        $this->app->bind(Repositories\WalletChargingPackageInterface::class, Eloquent\WalletChargingPackageRepository::class);
        $this->app->bind(Repositories\WheelOfFortuneInterface::class, Eloquent\WheelOfFortuneRepository::class);

        $this->app->singleton('cosmo', function ($app) {
            return new Cosmo;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
