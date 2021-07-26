<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashTillModel;
use Auth;

class CashTillController extends Controller
{
    public function index(Request $request)
    {
        //dd($request->all());
        $date = $request->cash_till_date;
       //  dd($date);
        $user_id = Auth::user()->id;
        // if( $date == ''){
        // $date = date("Y-m-d");
        // }
        $create = CashTillModel::updateOrCreate([
            'cash_till_date' => $date,
        ],[
            'clinic_id' => $request->clinic_id,
            'opening_balance' => $request->yester_day_balance,
            'cash_received_today' =>$request->cash_received,
            'any_refunds' => $request->any_refunds,
            'given_money' => $request->given_money,
            'extra_money_added' => $request->extra_money_added,
            'created_by' => $user_id,
            'manager_id' => $request->manager_id,
            'end_balance' => $request->end_balance,
        ]);

        if ($create) {
            return 1;
        } else {
            return 0;
        }
        
    }

}
