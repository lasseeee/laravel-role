<?php

namespace Lasseeee\Role;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lasseeee\Role\Commands\RoleCommand;

class RoleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-role')
            ->hasConfigFile()
            ->hasMigration('create_roles_table')
            ->hasMigration('create_role_user_table');
    }
}
