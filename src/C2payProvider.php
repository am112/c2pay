<?php
declare(strict_types=1);

namespace Am112\C2pay;

use Am112\C2pay\Commands\C2payLoggerCleanupCommand;
use Am112\C2pay\Services\C2payApi;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class C2payProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('c2pay')
            ->hasConfigFile()
            ->hasMigration('create_migration_c2pay_logger_table')
            ->hasCommand(C2payLoggerCleanupCommand::class);
    }

    public function registeringPackage()
    {
        // Bind core services for DI
        $this->app->singleton(C2payApi::class, fn() => new C2payApi());
        $this->app->singleton(C2pay::class, fn($app) => new C2pay($app->make(C2payApi::class)));
    }
}
