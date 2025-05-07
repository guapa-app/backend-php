<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;
use Filament\Http\Middleware\Authenticate;
use App\Filament\User\Widgets\ContractWidget;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class UserPanelProvider extends PanelProvider
{
    public function boot()
    {
        DB::getDoctrineSchemaManager()?->getDatabasePlatform()?->registerDoctrineTypeMapping('enum', 'string');
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('user')
            ->path('cms')
            ->authGuard('vendor-filament')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\\Filament\\User\\Resources')
            ->discoverResources(in: app_path('Filament/User/Resources/GuapaPlus'), for: 'App\\Filament\\User\\Resources\\GuapaPlus')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\\Filament\\User\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\\Filament\\User\\Widgets')
            ->discoverWidgets(in: app_path('Filament/User/Widgets/GuapaPlus'), for: 'App\\Filament\\User\\Widgets\\GuapaPlus')
            ->widgets([
                Widgets\AccountWidget::class,
                ContractWidget::class,
//                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationGroups([
                'Info',
                'Shop',
                'Guapa Plus',
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
