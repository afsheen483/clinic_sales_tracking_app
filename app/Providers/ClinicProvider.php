<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
class ClinicProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('clinic_array',DB::table('clinics')->where('is_deleted','=','0')->get());
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
