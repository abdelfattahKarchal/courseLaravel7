<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
/**
 * solution 1
 */
        //definition d'une autoridation avec Gate
        // Gate::define('post.update',function($user,$post){
        //     return $user->id === $post->user_id;
        // });

        // Gate::define('post.delete',function($user,$post){
        //     return $user->id === $post->user_id;
        // });

        // Gate::before(function($user,$ability){
        //     if($user->is_admin && in_array($ability,['post.delete'])){
        //         return true;
        //     }
        // });

        /**
         * Solution 2
         */

        // Gate::define("post.update","App\Policies\PostPolicy@update");
        // Gate::define("post.delete","App\Policies\PostPolicy@delete");

        /**
         * solution 3 (idéal)
         */
       // Gate::resource("post","App\Policies\PostPolicy");

       Gate::before(function($user,$ability){
            if($user->is_admin && in_array($ability,['update','restore','forceDelete','delete'])){
                return true;
            }
        });

        Gate::define('secret.page',function($user){
            return $user->is_admin;
        });
    }
}
