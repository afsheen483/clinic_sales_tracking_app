<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
class TrackStatusProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('track_status_array',DB::table('track_status')->get());
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
