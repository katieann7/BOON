<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route as Route;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!Route::has('passport')) {
            Passport::routes();
        }

        // Token Expiration
        Passport::tokensExpireIn(now()->addYears(100));
        Passport::refreshTokensExpireIn(now()->addYears(100));

        Passport::tokensCan([
            'delivery-module' => 'Delivery Module',
        ]);
    }
}
