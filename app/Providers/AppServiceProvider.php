<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Load helper functions early
        if (file_exists($helperPath = app_path('helpers.php'))) {
            require_once $helperPath;
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Blade directives for permissions
        \Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        \Blade::if('role', function ($role) {
            if (is_array($role)) {
                return auth()->check() && auth()->user()->hasAnyRole($role);
            }
            return auth()->check() && auth()->user()->hasRole($role);
        });

        \Blade::if('anyPermission', function (array $permissions) {
            return auth()->check() && auth()->user()->hasAnyPermission($permissions);
        });
    }
}
