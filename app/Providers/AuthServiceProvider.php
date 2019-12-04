<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
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
     * @param GateContract $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('isSuperAdmin',function ($user){
            return $user->user_type == 'Super Admin';
        });

        $gate->define('isAdmin',function ($user){
            return $user->user_type == 'Admin';
        });
        $gate->define('isInCharge',function ($user){
            return $user->user_type == 'In-Charge';
        });

        $gate->define('hasUpdated',function ($user){
            return $user->updated == 1;
        });

        $gate->define('canLogin',function ($user){
            return $user->status == 0;
        });
    }
}
