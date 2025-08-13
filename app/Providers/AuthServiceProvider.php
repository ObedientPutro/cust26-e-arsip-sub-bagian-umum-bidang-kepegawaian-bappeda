<?php

namespace App\Providers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('is-admin', function (User $user) {
            return $user->role === UserRoleEnum::Admin;
        });

        Gate::define('is-lead', function (User $user) {
            return $user->role === UserRoleEnum::Lead;
        });

        Gate::define('is-employee', function (User $user) {
            return $user->role === UserRoleEnum::Employee;
        });

//        Gate::define('can-create-dispositions', function (User $user) {
//            return in_array($user->role, ['admin', 'pimpinan']);
//        });
    }
}
