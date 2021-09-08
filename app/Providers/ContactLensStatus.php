<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
class ContactLensStatus extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('contact_lens_status_array',DB::table('contact_lens_status')->get());
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
