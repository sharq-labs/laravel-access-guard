<?php

namespace Sharqlabs\LaravelAccessGuard;

use Sharqlabs\LaravelAccessGuard\Commands\AddAccessRecordCommand;
use Sharqlabs\LaravelAccessGuard\Commands\RemoveWhitelistedIpCommand;
use Sharqlabs\LaravelAccessGuard\Commands\ShowWhitelistedIpsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;

class LaravelAccessGuardServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-access-guard')
            ->hasConfigFile('access-guard') // Specify the config file name
            ->hasViews()
            ->hasMigration('create_laravel_access_guard_table');
    }

    /**
     * Boot the package services.
     */
    public function boot()
    {
        parent::boot();

        // Define rate limiter for Access Guard
        $this->defineRateLimiter();

        // Register custom session driver
        $this->registerCustomSessionDriver();

        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/access-guard.php' => config_path('access-guard.php'),
        ], 'config');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-access-guard');

        // Register console commands if running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddAccessRecordCommand::class,
                ShowWhitelistedIpsCommand::class,
                RemoveWhitelistedIpCommand::class,
            ]);
        }
    }

    /**
     * Register package services.
     */
    public function register()
    {
        parent::register();

        // Merge default configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/access-guard.php',
            'access-guard'
        );
    }

    /**
     * Define rate limiter for Access Guard.
     */
    protected function defineRateLimiter(): void
    {
        RateLimiter::for('access-guard', function ($request) {
            // Fetch rate limit settings from config
            $maxAttempts = config('access-guard.rate_limit.requests', 5); // Default: 5 attempts
            $resetInterval = config('access-guard.rate_limit.reset_interval', 1); // Default: 1 minute

            return \Illuminate\Cache\RateLimiting\Limit::perMinutes($resetInterval, $maxAttempts)->by($request->ip());
        });
    }

    /**
     * Register a custom session driver for Access Guard.
     */
    protected function registerCustomSessionDriver(): void
    {
        Session::extend('access-guard', function ($app) {
            config([
                'session.driver' => config('access-guard.session.driver', 'file'),
                'session.cookie' => config('access-guard.session.cookie', 'access_guard_session'),
                'session.lifetime' => config('access-guard.session.lifetime', 120),
                'session.files' => config('access-guard.session.files', storage_path('framework/sessions/access-guard')),
            ]);

            return $app->make('session')->driver(config('session.driver'));
        });

    }
}
