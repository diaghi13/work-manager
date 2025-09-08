<?php

namespace App\Providers\Filament;

use App\CustomRegister;
use App\Filament\App\Pages\Auth\EditProfile;
use App\Filament\App\Pages\Auth\Login;
use App\Http\Middleware\RegisteredDatabaseHandlerMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\View\View;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
                RegisteredDatabaseHandlerMiddleware::class,
            ], isPersistent: true)
//            ->routes(function ($routes) {
//                $routes->prefix('app');
//            })
            ->databaseTransactions()
            ->sidebarCollapsibleOnDesktop()
            ->spa()
            ->plugins([
                new TableLayoutTogglePlugin,
                FilamentFullCalendarPlugin::make()
                    ->timezone('Europe/Rome')
                    ->config([
                    'defaultView' => 'dayGridMonth',
                    'headerToolbar' => [
                        'left' => 'prev,next today',
                        'center' => 'title',
                        'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
                    ],
                    'views' => [
                        'dayGridMonth' => [
                            'buttonText' => __('app.full_calendar.month'),
                            'titleFormat' => ['year', 'month'],
                        ],
                        'timeGridWeek' => [
                            'buttonText' => __('app.full_calendar.week'),
                            'titleFormat' => ['year', 'month', 'day'],
                        ],
                        'timeGridDay' => [
                            'buttonText' => __('app.full_calendar.day'),
                            'titleFormat' => ['year', 'month', 'day'],
                        ],
                    ],
                    'firstDay' => 1,
                    'locale' => app()->getLocale(),
                    'navLinks' => true,
                    'selectable' => true,
                    'selectMirror' => true,
                    'dayMaxEvents' => true,
                    'weekNumbers' => false,
                    'weekNumberCalculation' => 'ISO',
                ]),
            ])
            ->login()
            ->registration(CustomRegister::class)
            ->profile(EditProfile::class);
            //->renderHook(PanelsRenderHook::SIDEBAR_NAV_START, fn(): View => \view('components.tenant-switcher'));
    }
}
