<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\BillingCompanyInvoiceModel;
use App\Models\BillingCompanyInvoiceDetailModel;
class BillingCompanyInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $invoice_head_id = $request->input('invoice_head_id');
       // dd($invoice_head_id);
        $p_id = explode(',', $invoice_head_id[0]); 
       // dd($p_id);
        $user_id = Auth::user()->id;
        $date = date("Y-m-d h:i:s");
        $invoice_date = date("Y-m-d");
        $invoice_head_id = $request->invoice_head_id;
        $image=$request->file('image');
        $file = $image->getClientOriginalName();
        $image->move('upload',$file);
        $base_path = "upload/";

        $billing_company_invoice_id = BillingCompanyInvoiceModel::create([
            'count_of_patients' => $request->count_patient,
            'insurance_collection_amount' =>$request->total_insurance_collection,
            'billing_invoice_date' => $invoice_date,
            'billing_company_percent' => $request->billing_company_percentage,
            'invoice_amount' => $request->invoice_amount,
            'invoice_file' => $base_path.$file,
            'invoice_title' => $request->invoice_name,
            'created_at' => $date,
            'created_by' => $user_id,
            'is_deleted' => '0',
        ])->id;

        if(is_array($p_id)){
            foreach ($p_id as $id) {
                BillingCompanyInvoiceDetailModel::create([
                    'invoice_head_id' => $id,
                    'billing_company_invoice_id' => $billing_company_invoice_id,
                    'created_at' => $date,
                    'created_by' => $user_id,
                    'is_deleted'=>'0',
                ]);
            }
        }else{
            BillingCompanyInvoiceDetailModel::create([
                'invoice_head_id' => $invoice_head_id,
                'billing_company_invoice_id' => $billing_company_invoice_id,
                'created_at' => $date,
                'created_by' => $user_id,
                'is_deleted'=>'0',
            ]);
        }
        return redirect("tagged_cases/all");

       
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
