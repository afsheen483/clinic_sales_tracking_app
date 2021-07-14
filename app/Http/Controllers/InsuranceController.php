<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InsuranceModel;
use Auth;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $insurances = InsuranceModel::where('is_deleted','0')->get();
        return view('Insurance.index',compact('insurances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $insurance = new InsuranceModel();
         if ($id > 0) {
             $insurance = InsuranceModel::find($id);
         }
        // dd($insurance);
         return view('Insurance.forms',compact('insurance'));
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
        InsuranceModel::create([
        'insurance_title'=>$request->insurance_title,
        'created_at' => $date,
        'created_by'=>$id,
        'is_deleted'=>0,
        ]);
        return redirect()->route('insurance.index');
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
        InsuranceModel::where('id','=',$id)->update([
        'insurance_title'=>$request->insurance_title,
        'modified_at' => $date,
        'modified_by'=>$user_id,
        ]);
        return redirect()->route('insurance.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //dd($id);
        InsuranceModel::where('id','=',$id)->update([
            'is_deleted' => 1,
            ]);
            return redirect()->route('insurance.index');
    }
}
