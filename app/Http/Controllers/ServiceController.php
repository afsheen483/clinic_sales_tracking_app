<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceModel;
use Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = ServiceModel::where('is_deleted','=','0')->get();
        return view('Service.index',compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $services = new ServiceModel();
         if ($id > 0) {
             $services = ServiceModel::find($id);
         }
        // dd($insurance);
         return view('Service.forms',compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $is_product = $request->is_product;
        $is_insured = $request->is_insured;
        if($is_product == ''){
            $is_product = 0;
        }
        if ($is_insured == '') {
            $is_insured = 0;
        }
        $id = Auth::user()->id;
        $date = date("Y-m-d h:i:s");
        ServiceModel::create([
        'title'=>$request->service_title,
        'unit_price'=>$request->unit_price,
        'is_insured'=>$is_insured,
        'is_product'=>$is_product,
        'created_at' => $date,
        'created_by'=>$id,
        'is_deleted'=>0,
        ]);
        return redirect()->route('service.index');
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
        ServiceModel::where('id','=',$id)->update([
            'title'=>$request->service_title,
            'unit_price'=>$request->unit_price,
            'is_insured'=>$request->is_insured,
            'is_product'=>$request->is_product,
            'modified_at' => $date,
            'modified_by'=>$user_id,
        ]);
        return redirect()->route('service.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ServiceModel::where('id','=',$id)->update([
            'is_deleted' => 1,
            ]);
            return redirect()->route('service.index');
    }
}
