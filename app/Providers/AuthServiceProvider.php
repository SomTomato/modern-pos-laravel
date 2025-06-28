<?php

namespace App\Providers;

// Add this import
use Illuminate\Support\Facades\Gate;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // THE FIX: Define a rule (a "Gate") named 'admin-only'.
        // It returns true only if the logged-in user's role is 'admin'.
        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });
    }
}
