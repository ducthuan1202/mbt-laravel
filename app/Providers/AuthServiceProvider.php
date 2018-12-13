<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // manager
        Gate::define('admin', function ($user) {
            return true;
        });

        // manager
        Gate::define('manager', function ($user) {
            return ($user->role_id === User::MANAGER_ROLE);
        });

        // employee
        Gate::define('employee', function ($user) {
            return ($user->role_id === User::EMPLOYEE_ROLE);
        });
    }
}
