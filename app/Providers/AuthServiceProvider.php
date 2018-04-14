<?php

namespace App\Providers;

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
        'App\Room' => 'App\Policies\RoomPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Reply' => 'App\Policies\ReplyPolicy',
        'App\Mark' => 'App\Policies\MarkPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
