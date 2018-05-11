<?php

namespace App\Providers;

use App\Room;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('rooms.index', function($view){
            $view->with('desc', Room::desc());
            $view->with('asc', Room::asc());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
