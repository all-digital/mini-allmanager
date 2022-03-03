<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        $urlAllmanager = config('apiUrl.apiAllmanager');

        //dd($urlAllmanager);

        View::share('urlAllmanager', $urlAllmanager);

    }//end methods

}//end class
