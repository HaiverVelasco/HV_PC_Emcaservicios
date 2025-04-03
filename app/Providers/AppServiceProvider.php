<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
        // Establecer longitud predeterminada para strings
        Schema::defaultStringLength(191);

        // Establecer el formato de fechas
        Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_ES.utf8');
    }
}
