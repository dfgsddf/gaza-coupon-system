<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\HasPermissions;

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
        // Permission Blade directives
        Blade::if('permission', function ($permission) {
            return HasPermissions::userHasPermission($permission);
        });

        Blade::if('anyPermission', function ($permissions) {
            return HasPermissions::userHasAnyPermission($permissions);
        });

        Blade::if('allPermissions', function ($permissions) {
            return HasPermissions::userHasAllPermissions($permissions);
        });

        // Role Blade directives
        Blade::if('role', function ($role) {
            return HasPermissions::getUserRole() === $role;
        });

        Blade::if('admin', function () {
            return HasPermissions::userIsAdmin();
        });

        Blade::if('charity', function () {
            return HasPermissions::userIsCharity();
        });

        Blade::if('store', function () {
            return HasPermissions::userIsStore();
        });

        Blade::if('beneficiary', function () {
            return HasPermissions::userIsBeneficiary();
        });
    }
}
