<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethodModel;
use Auth;
class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = PaymentMethodModel::where('is_deleted','=','0')->get();
        return view('Payment_Methods.index',compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $payments = new PaymentMethodModel();
        if ($id > 0) {
            $payments = PaymentMethodModel::find($id);
        }
        return view('Payment_Methods.forms',compact('payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Auth::user()->id;
        $date = date("Y-m-d h:i:s");
        PaymentMethodModel::create([
        'title'=>$request->title,
        'created_at' => $date,
        'created_by'=>$id,
        'is_deleted'=>0,
        ]);
        return redirect()->route('payments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $date = date("Y-m-d h:i:s");
        PaymentMethodModel::where('id','=',$id)->update([
        'title'=>$request->title,
        'modified_at' => $date,
        'modified_by'=>$user_id,
        ]);
        return redirect()->route('payments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentMethodModel::where('id','=',$id)->update([
            'is_deleted' => 1,
            ]);
            return redirect()->route('payments.index');
    }
}
