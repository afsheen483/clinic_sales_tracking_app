<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use App\Models\User;
class Service extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('*',function($view){
            $view->with('service_array',DB::table('services')->where('is_insured','=','1')->where('is_product','=','0')->where('is_deleted','=','0')->get());
            });
            view()->composer('*',function($view){
                $view->with('extra_service_array',DB::table('services')->where('is_insured','=','0')->where('is_product','=','0')->where('is_deleted','=','0')->get());
                });
                view()->composer('*',function($view){
                    $view->with('product_array',DB::table('services')->where('is_insured','=','0')->where('is_product','=','1')->where('is_deleted','=','0')->get());
                    });
                    view()->composer('*',function($view){
                        $view->with('doctor_user_array',User::role('doctor')->get());
                        });
                        view()->composer('*',function($view){
                            $view->with('manager_user_array',User::role('Manager')->get());
                            });
                            view()->composer('*',function($view){
                                $view->with('location_array',DB::select("SELECT DISTINCT year(invoice_date) AS year FROM invoice_head ORDER BY year(invoice_date)"));
                                });
                                view()->composer('*',function($view){
                                    $view->with('first_date',DB::select("SELECT CONCAT(year(now()), '-01-01') as start"));
                                    });
                                    view()->composer('*',function($view){
                                        $view->with('last_date',DB::select("SELECT CONCAT(year(now()),'-12-31') as end
                                        "));
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
