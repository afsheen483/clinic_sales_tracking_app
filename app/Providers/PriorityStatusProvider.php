<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class PriorityStatusProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('priority_array',DB::table('priority_status')->get());
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
