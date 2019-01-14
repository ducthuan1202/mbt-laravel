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
            return $user->role === User::ADMIN_ROLE;
        });

        // employee
        Gate::define('employee', function ($user) {
            return ($user->role === User::EMPLOYEE_ROLE || $user->role === User::ADMIN_ROLE);
        });

        # view order
        Gate::define('view-order', function ($user, $order) {
            return $user->id == $order->user_id;
        });
    }
}
