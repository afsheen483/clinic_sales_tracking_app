<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
class ContactLensProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('contact_lens_array',DB::table('contact_lens')->where('is_deleted','=',0)->get());
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
