<?php

namespace App\Providers;

use App\Enums\RoleEnum;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

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
        Gate::define('viewPulse', function (User $user) {
            return $user->role_id==RoleEnum::SuperAdminID->value;
        });
        Cashier::useSubscriptionModel(Subscription::class);
    }
}
