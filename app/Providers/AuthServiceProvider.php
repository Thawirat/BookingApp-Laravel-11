<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // Import the Gate facade
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
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

        Gate::define('manage-buildings', function ($user) {
            return $user->isAdminOrSubAdmin();
        });

        Gate::define('admin-only', function ($user) {
            return $user->hasRole('admin');
        });

        // กำหนด Gate สำหรับ Sub-admin
        Gate::define('sub-admin-only', function (User $user) {
            return $user->hasRole('sub-admin');
        });

        Gate::define('user-only', function ($user) {
            return $user->isUser();
        });

        Gate::define('admin-or-subadmin', function ($user) {
            return $user->isAdminOrSubAdmin();
        });
    }
}
