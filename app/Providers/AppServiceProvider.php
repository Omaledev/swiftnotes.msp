<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

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
    //     Redirect::macro('toLogin', function () {
    //     return redirect()->route('auth.login');
    //    });
    if($this->app->environment('production')) {
        URL::forceScheme('https');
    }
    }
}
