<?php

namespace App\Providers\Filament;

use App\Filament\Resources\UserResource\Widgets\UserByGender;
use App\Filament\Widgets\AccountsOverView;
use App\Filament\Widgets\TalensAge;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Support\Assets\Js;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandLogo(asset('logo.svg'))
            ->darkModeBrandLogo(asset('dark-logo.svg'))
            ->darkMode(true)
            ->brandLogoHeight('2rem')
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->databaseNotifications()
            ->colors([
                'primary' => '#5Fb099',
                'gray' => '#464646',
                'info' => '#1877f2',
                'success' => '#77DCBF',
                'warning' => '#DDB207',
                'danger' => '#D40303',
                'gold' => '#FFD700',
                'silver' => '#c0c0c0',
                'bronze' => '#CD7F32',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountsOverView::class,
                TalensAge::class,
                UserByGender::class,
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
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('System Settings')
                    ->icon('icon-controls'),
                NavigationGroup::make()
                    ->label('Admin Settings')
                    ->icon('icon-repair'),
             ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
