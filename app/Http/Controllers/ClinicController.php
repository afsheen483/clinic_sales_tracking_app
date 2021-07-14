<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicModel;
use Auth;
class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clinics = ClinicModel::where('is_deleted','=','0')->get();
        return view('Clinics.index',compact('clinics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $clinics = new ClinicModel();
        if ($id > 0) {
            $clinics = ClinicModel::find($id);
        }
        return view('Clinics.forms',compact('clinics'));
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
        ClinicModel::create([
        'clinic_name'=>$request->clinic_name,
        'location'=>$request->location,
        'address'=>$request->address,
        'phone_no'=>$request->phone_no,
        'created_at' => $date,
        'created_by'=>$id,
        'is_deleted'=>0,
        ]);
        return redirect()->route('clinics.index');
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
        ClinicModel::where('id','=',$id)->update([
            'clinic_name'=>$request->clinic_name,
            'location'=>$request->location,
            'address'=>$request->address,
            'phone_no'=>$request->phone_no,
        'modified_at' => $date,
        'modified_by'=>$user_id,
        ]);
        return redirect()->route('clinics.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClinicModel::where('id','=',$id)->update([
            'is_deleted' => 1,
            ]);
            return redirect()->route('clinics.index');
        }
}
