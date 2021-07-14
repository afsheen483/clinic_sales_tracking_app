<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
class PaymentMethodProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('payment_method_array',DB::table('payment_methods')->where('is_deleted','=','0')->get());
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
