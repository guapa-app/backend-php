<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AffiliateMarketeerPanelProvider extends PanelProvider
{
    public function boot()
    {
        DB::getDoctrineSchemaManager()?->getDatabasePlatform()?->registerDoctrineTypeMapping('enum', 'string');
        FilamentColor::register([
            'cyan' => Color::Cyan,
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('affiliate-marketeer')
            ->path('affiliate-marketeer-cms')
            ->authGuard('affiliate-marketeer-filament')
            ->login()
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->topNavigation()
            ->discoverResources(in: app_path('Filament/AffiliateMarketeer/Resources'), for: 'App\\Filament\\AffiliateMarketeer\\Resources')
            ->discoverPages(in: app_path('Filament/AffiliateMarketeer/Pages'), for: 'App\\Filament\\AffiliateMarketeer\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/AffiliateMarketeer/Widgets'), for: 'App\\Filament\\AffiliateMarketeer\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
