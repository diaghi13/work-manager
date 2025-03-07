<?php

namespace App\Providers;

use App\Http\Middleware\RegisteredDatabaseHandlerMiddleware;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(config('app.env') === 'production') {
            URL::forceScheme(request()->server->set('HTTPS', request()->header('X-Forwarded-Proto', 'https') == 'https' ? 'on' : 'off'));
        }

        Livewire::addPersistentMiddleware([
            RegisteredDatabaseHandlerMiddleware::class
        ]);
    }
}
