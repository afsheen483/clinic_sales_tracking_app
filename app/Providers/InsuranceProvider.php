<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
class InsuranceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('insurance_array',DB::table('insurances')->where('is_deleted','=','0')->get());
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
