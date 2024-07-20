<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

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
        $company = Company::get();
        if (!empty($company) && !empty($company->email_channel)) Config::set('mail.default', $company->email_channel);
        User::observe(UserObserver::class);
    }
}
