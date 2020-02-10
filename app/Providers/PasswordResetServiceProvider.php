<?php

namespace App\Providers;

use App\PasswordBrokerManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider as ParentPasswordResetServiceProvider;

class PasswordResetServiceProvider extends ParentPasswordResetServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
