<?php

namespace Rampokker\LaravelCrudGenerator;

use Rampokker\LaravelCrudGenerator\Commands\LaravelCrudGeneratorCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCrudGeneratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel_crud_generator')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_crud_generator_table')
            ->hasCommand(LaravelCrudGeneratorCommand::class);
    }
}
