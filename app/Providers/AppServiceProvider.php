<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * j injecte des variables qui se trouvent sur la class qu on a crée (ActivityComposer) et précisement
         * dans la méthode (compose) sur la vue mentionée (posts.index)
         */
        view()->composer(['posts.index','posts.show'],ActivityComposer::class);
    }
}
