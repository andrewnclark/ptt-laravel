<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the EventServiceProvider
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        \App\Models\Crm\Company::observe(\App\Observers\CompanyObserver::class);
        \App\Models\Crm\Contact::observe(\App\Observers\ContactObserver::class);
    }
}
