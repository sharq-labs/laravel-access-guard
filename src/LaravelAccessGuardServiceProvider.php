<?php

namespace Sharqlabs\LaravelAccessGuard;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Sharqlabs\LaravelAccessGuard\Commands\LaravelAccessGuardCommand;

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
            ->hasMigration('create_laravel_access_guard_table') // Add migration support
            ->hasCommand(LaravelAccessGuardCommand::class);
    }

    /**
     * Boot the package services.
     */
    public function boot()
    {
        parent::boot();

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

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddAccessRecordCommand::class,
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
            'access-guard-config'
        );
    }
}
