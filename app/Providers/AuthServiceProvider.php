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
        Gate::define('admin', function () {
            return (int)auth()->user()->role === User::ADMIN_ROLE;
        });

        // employee
        Gate::define('employee', function () {
            return (int)auth()->user()->role === User::EMPLOYEE_ROLE;
        });

        # view order
        Gate::define('view-order', function ($order) {
            return (int)auth()->id() === (int)$order->user_id || (int)auth()->role === User::ADMIN_ROLE;
        });
    }
}
