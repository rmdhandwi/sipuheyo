<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RekamMedik;
use App\Observers\RekamMedikObserver;
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
    public function boot()
    {
        RekamMedik::observe(RekamMedikObserver::class);
    }
}
