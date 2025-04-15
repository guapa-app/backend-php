<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function boot()
    {
        DB::getDoctrineSchemaManager()?->getDatabasePlatform()?->registerDoctrineTypeMapping('enum', 'string');

        FilamentColor::register([
            'indigo' => Color::Indigo,
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin-cms')
            ->authGuard('admin-filament')
            ->login()
            ->colors([
                'primary' => Color::Sky,
            ])
            ->topNavigation()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Shop')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Info')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label(' Appointment')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Blog')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('User & Vendor')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Finance')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Admin Setting')
                    ->collapsed(),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->plugin(
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(config('app.available_locales')),
            )
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Admin\Pages\MyCustomDashboardPage::class, // Add this line
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
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
