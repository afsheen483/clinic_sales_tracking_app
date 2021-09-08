<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDetailModel;
use App\Models\InvoiceHeadModel;
use App\Models\InvoicePaymentModel;
use App\Models\PaymentMethodModel;
use Auth;
use DB;
use App\Models\ContactLensTrackerModel;
class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($filter,$date,$clinic_id)
    {
        $reports = new DB();
        $total_patients = new DB();
        if ($filter == 'patient_checkout') {
                $reports = DB::select("SELECT base.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(p.title) AS payment_title FROM (
                    SELECT
                                    h.id,
                                    d.invoice_head_id,
                                    d.quantity,
                                    (i.insurance_title) AS p_insurance_title,
                                       (si.insurance_title) AS s_insurance_title,                              
                                  h.is_completed,
                                    u.name,
                                    d.pro_unit_price,
                                       CONCAT('$', SUM(d.copayment)) AS total_amount,
                                    CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,
                                    GROUP_CONCAT(DISTINCT s.title) AS title,
                                    h.patient_firstname,
                                    h.patient_lastname,
                                    h.primary_insurance_id,  
                                    h.secondary_insurance_id,
                                    h.remarks
                                FROM
                                    invoice_head h
                            LEFT JOIN invoice_details d ON d.invoice_head_id =  h.id 
                            LEFT JOIN services s ON s.id = d.service_id
                                LEFT JOIN users u ON u.id = h.doctor_id
                                   LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                               
                                WHERE
                                    h.is_deleted = 0 
                                    GROUP BY d.invoice_head_id
                                    ORDER By h.id desc
                        ) base
                    JOIN invoice_payments ip ON ip.invoice_head_id = base.invoice_head_id
                    JOIN payment_methods p ON p.id = ip.payment_method_id
                    GROUP BY base.invoice_head_id
                    ORDER BY base.id DESC

                
            
            
            ");

        }
       
        if ($filter == 'end_of_the_day' && $date && $clinic_id) {
           // dd($date."clinic_id".$clinic_id);
            $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title SEPARATOR  ' \n ') AS payment_title FROM (
                SELECT base.*,  d.quantity,  d.pro_unit_price, CONCAT('$', SUM(d.copayment)) AS total_amount,
                            CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment, GROUP_CONCAT(DISTINCT s.title SEPARATOR  ',') AS title FROM (
                                        SELECT
                                                    h.id,
                                                    h.is_completed,
                                                    (i.insurance_title) AS p_insurance_title,
                                                    (si.insurance_title) AS s_insurance_title,                              
                                                    h.invoice_date,
                                                    u.name,
                                                     CONCAT('$',CONVERT(h.insurance_balance,decimal(10,2))) AS insurance_balance,
                                                    CONCAT('$',CONVERT(h.total_balance,decimal(10,2))) AS total_balance,
                                                    h.is_out_of_pocket,
                                                    h.patient_firstname,
                                                    h.patient_lastname,
                                                    h.primary_insurance_id,  
                                                    h.secondary_insurance_id,
                                                    h.remarks
                                                FROM
                                                invoice_head h
                                                
                                                LEFT JOIN users u ON u.id = h.doctor_id
                                                LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                                LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                                            
                                                WHERE
                                                h.is_deleted = 0 AND h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."'
                                           
                                                    GROUP BY h.id
                                                    ORDER By h.id desc
                                        ) base
                                        LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                        LEFT JOIN services s ON s.id = d.service_id
                                        GROUP BY base.id
                                        ORDER BY base.id DESC 
                                        ) AS result 
                                           LEFT JOIN invoice_payments ip ON ip.invoice_head_id = result.id
                                        LEFT JOIN payment_methods p ON p.id = ip.payment_method_id
                                        GROUP BY result.id
                                        ORDER BY result.id DESC 
                                        ");

                                        $yesterday_balance = DB::select("SELECT
                                            c.*,
                                            c.opening_balance AS yesterday_balance,
                                            c.cash_till_date AS invoice_date
                                        FROM
                                            cash_till c
                                        JOIN invoice_head h ON
                                            h.clinic_id = c.clinic_id
                                        WHERE  c.clinic_id = '".$clinic_id."' AND c.cash_till_date = '".$date."'
                                        GROUP BY c.cash_till_date ");
                                        //dd($yesterday_balance);

                                        if($yesterday_balance == NULL){
                                            $yesterday_balance = DB::select("SELECT
                                                0 AS any_refunds,
                                                0 AS extra_money_added,
                                                0 AS given_money,
                                                (SELECT SUM(ip.amount) FROM invoice_head ih 
                                        JOIN invoice_payments ip ON
                                            ip.invoice_head_id = ih.id WHERE  ih.invoice_date = '".$date."' AND ih.clinic_id = '".$clinic_id."'  AND h.is_deleted = 0 AND ip.payment_method_id = 2 ) AS cash_received_today,
                                                c.end_balance AS yesterday_balance,
                                                c.cash_till_date AS invoice_date
                                            FROM
                                                cash_till c
                                            JOIN invoice_head h ON
                                                h.clinic_id = c.clinic_id
                                            WHERE  c.clinic_id = '".$clinic_id."'  AND c.cash_till_date < '".$date."'
                                            ORDER BY c.cash_till_date  desc LIMIT 1");
                                        }

                                      //dd($yesterday_balance);
                                       $today_balance = DB::select("SELECT
                                            h.invoice_date,
                                          CONVERT(IFNULL((p.amount),0),decimal(10,2)) AS today_balance
                                        FROM
                                        invoice_head h 
                                        JOIN invoice_payments p ON
                                            p.invoice_head_id = h.id
                                        WHERE
                                            h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."' 
                                            AND h.is_deleted = 0 AND p.payment_method_id = 2
                                        ");
                                       // dd($today_balance);
                       //$today_balance =  InvoiceHeadModel::select('invoice_head.*',DB::raw('SUM(invoice_details.copayment) as today_balance'))->join('invoice_details','invoice_head.id','invoice_details.invoice_head_id')->where('invoice_head.invoice_date',$date)->get();
                // $saling_percentage[] = new DB();
                
                $saling_percentage = DB::select("SELECT base.*, CONCAT(CONVERT(base.fundus_ratio + base.medical_ratio,decimal(10,2)),'%') AS fun_medical_ratio FROM(
                    SELECT
                            COUNT(DISTINCT h.id) AS total_patients,
                            h.clinic_id,
                            CONCAT(22,'%') AS patient_target,
                    CONCAT(CONVERT( SUM(
                            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
                            END
                            )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS fundus_ratio,
                            CONCAT(CONVERT( SUM(
                            CASE WHEN d.service_id = 3 THEN 1 ELSE 0
                            END
                            )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS medical_ratio, CONCAT(70,'%') AS medical_target,
                            CONCAT(CONVERT(SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS cl_ratio,  CONCAT(50,'%') AS cl_target,
                            SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END) AS cl,
                            SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END) AS oasis,
                            CONCAT(CONVERT( SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS oasis_ratio, CONCAT(20,'%') AS oasis_target,
                            CONCAT(20,'%') AS family_target,
                                (SELECT COUNT(ih.family_upsell ) FROM invoice_head ih WHERE ih.invoice_date = h.invoice_date AND ih.clinic_id = h.clinic_id AND ih.family_upsell = 1) AS family_upsell_count  
                            FROM
                            invoice_head h
                           LEFT JOIN invoice_details d ON
                            d.invoice_head_id = h.id
                            WHERE h.is_deleted = 0 AND h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."'
)
AS base");  
                //dd($yesterday_balance);

                    $end_day = DB::select("SELECT EXISTS(SELECT * FROM cash_till WHERE cash_till_date = '".$date."') AS exist_value");
                    return view('Patient_Checkout.index',compact('yesterday_balance','reports','today_balance','saling_percentage','end_day'));

        }
        
       
        if ($filter == 'tagged_cases') {
            $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
                SELECT base.*, d.quantity, CONCAT('$', d.pro_unit_price) AS unit_price,
                                            CONCAT('$',SUM(d.copayment)) as copayment,
                                            CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,  GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                                SELECT
                                            h.id,
                                            h.is_completed,
                                            h.is_out_of_pocket,
                                           
                                            (i.insurance_title) AS p_insurance_title,
                                            (si.insurance_title) AS s_insurance_title,
                                            c.location,
                                            CONCAT('$',SUM(DISTINCT p.amount)) AS total_amount,
                                            p.payment_method_id,
                                            h.invoice_date,
                                            GROUP_CONCAT(DISTINCT pm.title) AS payment_title,
                                            u.name,
                                          
                                           
                                            h.patient_firstname,
                                            h.patient_lastname,
                                            h.primary_insurance_id,
                                            h.secondary_insurance_id,
                                            h.remarks
                                        FROM
                                        invoice_head h
                                          
                                            
                                            LEFT JOIN users u ON u.id = h.doctor_id
                                            LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                         LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                                       LEFT JOIN clinics c ON c.id = h.clinic_id
                                       LEFT JOIN tagged_invoices t ON t.invoice_head_id = h.id
                                        LEFT JOIN invoice_payments p ON p.invoice_head_id = h.id
                                        JOIN payment_methods pm ON pm.id = p.payment_method_id
                                        LEFT JOIN billing_company_invoices_details b ON b.invoice_head_id = h.id
                                        LEFT JOIN billing_company_invoices bi ON bi.id = b.billing_company_invoice_id
                                        WHERE
                                            h.is_deleted = 0 AND NOT EXISTS  (SELECT * FROM billing_company_invoices_details b WHERE b.invoice_head_id = h.id)
                                            GROUP BY h.id,i.id
                                            ORDER By h.id desc
                                    ) base
                                LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                LEFT JOIN services s ON s.id = d.service_id
                                GROUP BY base.id
                                            ORDER By base.id desc
                               ) AS result
                                LEFT JOIN invoice_payments ip ON ip.invoice_head_id = result.id
                                LEFT JOIN payment_methods p ON p.id = ip.payment_method_id
                                GROUP BY result.id
                                ORDER BY result.id DESC
                               
        ");
       
        }
         
        //$count_rows = count($invoice_head_data);
        return view('Patient_Checkout.index',compact('reports','total_patients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->id;

        $invoice_head_data[] = new InvoiceHeadModel();
        $invoice_patient_data[] = new InvoiceHeadModel();
        $invoice_payment_data[] = new InvoicePaymentModel();
        //$invoice_detail_data = new InvoiceDetailModel();
        if ($id > 0) {

            $invoice_patient_data = InvoiceHeadModel::select('invoice_head.*','clinics.location','users.name')->join('clinics','clinics.id','invoice_head.clinic_id')->join('users','invoice_head.doctor_id','users.id')->where('invoice_head.id','=',$id)->where('invoice_head.is_deleted','=','0')->get();
          //dd($invoice_patient_data);
          $invoice_head_data = DB::select("SELECT invoice_head_id,service_id,contact_lens_id, quantity, pro_unit_price, copayment, insurance_payment,
          patient_firstname,patient_lastname,clinic_id,doctor_id,primary_insurance_id,secondary_insurance_id,is_out_of_pocket,family_upsell,is_completed,invoice_amount,invoice_date,remarks, s.id,s.title FROM services s 
          LEFT JOIN 
          (
          SELECT d.invoice_head_id,d.service_id,d.contact_lens_id, d.quantity,d.pro_unit_price,d.copayment,d.insurance_payment,h.patient_firstname,h.patient_lastname,h.clinic_id,h.doctor_id,h.primary_insurance_id,h.secondary_insurance_id,h.is_out_of_pocket,h.family_upsell,h.is_completed,h.invoice_amount,h.invoice_date,h.remarks
          FROM `invoice_details` d  
          JOIN invoice_head h ON d.invoice_head_id = h.id
          WHERE d.invoice_head_id = '".$id."') AS i ON i.service_id = s.id  
          ORDER BY s.id           
           ");
           //dd($invoice_head_data);
           $invoice_payment_data = DB::select("SELECT m.title,invoice_head_id,amount,payment_method_id
           FROM payment_methods m 
          LEFT JOIN 
          (
          SELECT p.invoice_head_id,p.amount,p.payment_method_id
          FROM invoice_payments p  
          JOIN invoice_head h ON p.invoice_head_id = h.id
          WHERE p.invoice_head_id = '".$id."') AS i ON i.payment_method_id = m.id

            ");
  // dd($invoice_head_data);
        }
        return view('Patient_Checkout.forms',compact('invoice_head_data','invoice_patient_data','invoice_payment_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        global $is_completed;
        // dd($data);
        $i = 0;
        $copayment = 0;
        $insurance_payment = 0;
        $extra_copayment = 0;
        $quantity = 0;
        $unit_price = 0;
        global $claim_status;
        $amount = 0;
        $is_out_of_pocket = $request->is_out_of_pocket;
        $family_upsell = $request->family_upsell;
        if ($is_out_of_pocket == '') {
            $is_out_of_pocket = 0;
            $claim_status = 'Unclaimed';
        }elseif($is_out_of_pocket == 1){
            $is_completed = 1;
        }
        if ($family_upsell == '') {
            $family_upsell = 0;
        }

        $id = Auth::user()->id;
        $date = date("Y-m-d h:i:s");
       $invoice_head_id = InvoiceHeadModel::create([
                    'patient_firstname' => $request->patient_firstname,
                    'patient_lastname' => $request->patient_lastname,
                    'clinic_id' => $request->clinic_id,
                    'doctor_id' => $request->doctor_id,
                    'primary_insurance_id' => $request->primary_insurance_id,
                    'secondary_insurance_id' => $request->secondary_insurance_id,
                    'is_out_of_pocket' => $is_out_of_pocket,
                    'family_upsell' => $family_upsell,
                    'insurance_balance' => $request->insurance_balance,
                    'total_balance' => $request->total_balance,
                    'is_completed' => $is_completed,
                    'claim_status' => $claim_status,
                    'invoice_amount' => $request->total_amount,
                    'invoice_date' => $request->invoice_date,
                    'patient_balance' => $request->patient_balance,
                    'discount' => $request->discount,
                    'remarks' => $request->remarks,
                    'created_at' => $date,
                    'created_by'=>$id,
                    'is_deleted'=>0,
        ])->id;

        ContactLensTrackerModel::create([
            'invoice_head_id' => $invoice_head_id,
            'contact_lens_id' => $request->contact_lens_id,
        ]);
      
        $payment_val = 1;
foreach ($data as $value) {
   $i++;
            //print_r("value of i: "."" .$i++);
            if ($i>16) {break;}
    else
    {
        if($i<=3){              
                $copayment = $data["copayment_".$i];
                if($quantity > 0 || $unit_price > 0){
                    $copayment = $quantity * $unit_price;
                }
                $insurance_payment = $data["insurance_payment_".$i];
               // print_r("copayment_".$i." ".$copayment."<br>");
                //dd($insurance_payment."<br>");
                if($copayment >= 0 && $copayment != '' || $insurance_payment > 0){
               
                    InvoiceDetailModel::create([
                        'invoice_head_id' => $invoice_head_id,
                        'service_id' => $i,
                        'quantity' => $quantity,
                        'pro_unit_price' => $unit_price,
                        'copayment' => $copayment,
                        'insurance_payment' => $insurance_payment,
                        'insurance_payment_entered_by' => $id,
                        'insurance_payment_entered_at' => $date,
            
                    ]);
                    
                    
                }
            }
            if($i >= 4 && $i <= 12)
            {
            $copayment = $data["extra_copayment_".$i];
            //print_r("extra_copayment_".$i." ".$copayment."<br>");
            
            if($copayment > 0 ){
               
                InvoiceDetailModel::create([
                    'invoice_head_id' => $invoice_head_id,
                    'service_id' => $i,
                    'quantity' => $quantity,
                    'pro_unit_price' => $unit_price,
                    'copayment' => $copayment,
                    'insurance_payment' => 0,
                ]);
                
                
            }
            }
            if($i>=13 && $i<=16){
                $quantity = $data["quantity_".$i];
                $unit_price = $data["unit_price_".$i];
                // if($quantity > 0 || $unit_price > 0){
                   
                // }
               // print_r("quantity_".$i." ".$quantity."<br>");
                //print_r("unit_price_".$i." ".$unit_price."<br>");
                if($quantity > 0 || $unit_price > 0 ){
                    $copayment = $quantity * $unit_price;
                    InvoiceDetailModel::create([
                        'contact_lens_id' => $request->contact_lens_id,
                        'invoice_head_id' => $invoice_head_id,
                        'service_id' => $i,
                        'quantity' => $quantity,
                        'pro_unit_price' => $unit_price,
                        'copayment' => $copayment,
                        'insurance_payment' => 0,
                    ]);
                    
                    
                }
            }
            
        
            // if($copayment > 0 || $quantity > 0 || $unit_price > 0 || $insurance_payment > 0){
               
            //     InvoiceDetailModel::create([
            //         'invoice_head_id' => $invoice_head_id,
            //         'service_id' => $i,
            //         'quantity' => $quantity,
            //         'pro_unit_price' => $unit_price,
            //         'copayment' => $copayment,
            //         'insurance_payment' => $insurance_payment,
            //         'insurance_payment_entered_by' => $id,
            //         'insurance_payment_entered_at' => $date,
        
            //     ]);
                
                
            // }
            if ($payment_val<=4) {
                $amount = $data["payment_method_".$payment_val];
               // print_r("payment_method_".$payment_val." ".$amount."<br>");
                if ($amount > 0) {
                InvoicePaymentModel::create([
                    'invoice_head_id' => $invoice_head_id,
                    'amount' => $amount,
                    'payment_method_id' => $payment_val,
                    'created_at' => $date,
                    'created_by' => $id,
                    'is_deleted' => '0',
                ]);
                }
                $payment_val++;
            }


        }
    }
        // DB::delete("DELETE  FROM `invoice_details` WHERE `invoice_head_id` = '".$invoice_head_id."' and  copayment=0 and insurance_payment = NULL
        // and quantity = 0 and pro_unit_price = 0
        // ");
        DB::update("UPDATE `invoice_details` SET `insurance_payment_entered_by`= NULL ,`insurance_payment_entered_at` = NULL WHERE `insurance_payment` = 0 OR `insurance_payment` IS NULL");

    //    after entry
    $get_data = InvoiceHeadModel::where('id','=',$invoice_head_id)->get();
   // dd($get_data);
    $date = $get_data[0]->invoice_date;
   // dd($date);
    $clinic_id = $get_data[0]->clinic_id;
       return redirect('/reports/end_of_the_day/'.$date.'/'.$clinic_id);
       // Redirect::to('settings/photos?image_='. $image_);
       
       // return redirect()->route('patient_checkout_form.index');

     
        
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
    public function update(Request $request, $id,$pre)
    {
       //dd($pre);
        $data = $request->all();
        $is_com = InvoiceHeadModel::where('id','=',$id)->get();
        //dd($is_com);
        $is_completed = $is_com[0]->is_completed;
        if ($is_completed == 1) {
            $is_completed = 1;
        }
     // dd($data);
        $i = 0;
        global $copayment ;
        global $insurance_payment ;
        global $extra_copayment;
        global $quantity;
        global $unit_price;
        global $amount;
        $is_out_of_pocket = $request->is_out_of_pocket;
        $family_upsell = $request->family_upsell;
       // global $is_completed;
        $is_out_of_pocket = $request->is_out_of_pocket;
        $family_upsell = $request->family_upsell;
        
        if ($is_out_of_pocket == '') {
            $is_out_of_pocket = 0;
        }elseif($is_out_of_pocket == 1){
            $is_completed = 1;
        }
        if ($family_upsell == '') {
            $family_upsell = 0;
        }

        $user_id = Auth::user()->id;
        $date = date("Y-m-d h:i:s");
        InvoiceHeadModel::where('id','=',$id)->update([

                    'patient_firstname' => $request->patient_firstname,
                    'patient_lastname' => $request->patient_lastname,
                    'clinic_id' => $request->clinic_id,
                    'doctor_id' => $request->doctor_id,
                    'primary_insurance_id' => $request->primary_insurance_id,
                    'secondary_insurance_id' => $request->secondary_insurance_id,
                    'is_out_of_pocket' => $is_out_of_pocket,
                    'family_upsell' => $family_upsell,
                    'insurance_balance' => $request->insurance_balance,
                    'total_balance' => $request->total_balance,
                    'is_completed' => $is_completed,
                    'invoice_amount' => $request->total_amount,
                    'invoice_date' => $request->invoice_date,
                    'remarks' => $request->remarks,
                    'created_at' => $date,
                    'created_by'=>$user_id,
                    'is_deleted'=>0,
        ]);
        ContactLensTrackerModel::updateOrCreate([
            'invoice_head_id' => $invoice_head_id,
        ],[
            'contact_lens_id' => $request->contact_lens_id,
        ]);
       $payment_val = 1;
foreach ($data as $value) {
          $i++;
         
            

            if ($i>16) {break;}
    else
    {
        
        if($i<=3 ){              
            $copayment = $data["copayment_".$i];
            $insurance_payment = $data["insurance_payment_".$i];       
               // print_r("copayment_".$i." ".$copayment."<br>");
                print_r("insurance_payment_".$i.$insurance_payment."<br>");
                if($copayment == '' ){
                    //$copayment = 0;
                    // if ($copayment == 0) {
                    //     InvoiceDetailModel::where('invoice_head_id','=',$id)->where('service_id','=',$i)->delete();

                    // }
                }
                if($insurance_payment == '' ){
                   // $insurance_payment = 0;
                    // if ($insurance_payment == 0) {
                    //     InvoiceDetailModel::where('invoice_head_id','=',$id)->where('service_id','=',$i)->delete();

                    // }

                }
                  
                   
                    if($insurance_payment > 0 || $copayment >= 0 || $copayment == ''){
                        InvoiceDetailModel::updateOrCreate([
                            'invoice_head_id' => $id,
                            'service_id' => $i,
                            
                        ],[
                            'invoice_head_id' => $id,
                            'service_id' => $i,
                            'quantity' => 0,
                            'pro_unit_price' => 0,
                            'copayment' => $copayment,
                            'insurance_payment' => $insurance_payment,
                            'insurance_payment_entered_by' => $user_id,
                            'modified_by' => $user_id,
                            'modified_at' => $date,
                
                        ]);
                    }
                    
                    
                
            }
            
            if($i >= 4 && $i <= 12)
            {
            $copayment = $data["extra_copayment_".$i];
           
            if($copayment > 0 || $copayment == ''){
                print_r($copayment);
                InvoiceDetailModel::updateOrCreate([
                    'invoice_head_id' => $id,
                    'service_id' => $i,
                    
                ],[
                    'invoice_head_id' => $id,
                    'service_id' => $i,
                    'quantity' => 0,
                    'pro_unit_price' => 0,
                    'copayment' => $copayment,
                    'insurance_payment' => '',
                    'modified_by' => $user_id,
                    'modified_at' => $date,
        
                ]);
                
            }
            

           // print_r("extra_copayment_".$i." ".$copayment."<br>");
            }
            if($i>=13 && $i<=16){
                $quantity = $data["quantity_".$i];
                $unit_price = $data["unit_price_".$i];
                if($quantity > 0 &&  $unit_price > 0 ){
                   
                    $copayment = $quantity * $unit_price;
                    
                   
                     
                    InvoiceDetailModel::updateOrCreate([
                        'invoice_head_id' => $id,
                        'service_id' => $i,
                        
                    ],[
                        'invoice_head_id' => $id,
                        'contact_lens_id' => $request->contact_lens_id,
                        'service_id' => $i,
                        'quantity' => $quantity,
                        'pro_unit_price' => $unit_price,
                        'copayment' => $copayment,
                        'insurance_payment' => '',
                        'modified_by' => $user_id,
                        'modified_at' => $date,
            
                    ]);
                    
            
                }
            }

                //print_r("quantity_".$i." ".$quantity."<br>");
               // print_r("unit_price_".$i." ".$unit_price."<br>");
           
            
        
           
            if ($payment_val <= 4) {
              
                $amount = $data["payment_method_".$payment_val];
                print_r("payment_method_".$i." ".$amount."<br>");
                if ($amount > 0 || $amount == '' || $amount == NULL) {
                   // print_r($amount);
                InvoicePaymentModel::updateOrCreate([
                    'invoice_head_id'=>$id,
                    'payment_method_id' => $payment_val,
                ],[
                    
                    'amount' => $amount,
                    
                    'modified_at' => $date,
                    'modified_by' => $user_id,
                    'is_deleted' => '0',
                ]);
                }
                $payment_val ++;
            }
        }

        }
       // dd("done");
      $delete =  DB::delete("DELETE FROM `invoice_details` WHERE `invoice_head_id` = '".$id."' AND `quantity` = 0 AND `pro_unit_price` = 0 AND `copayment` IS NULL  AND (`insurance_payment` = 0 OR `insurance_payment` IS NULL)
        ");
      
        DB::delete("DELETE FROM `invoice_payments` WHERE `invoice_head_id` = '".$id."' AND `amount` IS NULL");
        DB::update("UPDATE `invoice_details` SET `insurance_payment_entered_by`= NULL ,`insurance_payment_entered_at` = NULL WHERE `insurance_payment` = 0 OR `insurance_payment` IS NULL");

        //InvoiceDetailModel::where('invoice_head_id','=',$id)->where('service_id','=',$i)->delete();
 
        //    dd(url()->previous());
    //     dd(\URL::to('/'));
        //if(url()->previous())
        if ($pre == 'insurance_payments') {
            return redirect('/report/insurance_payments/');
        }elseif($pre == 'patients_reports'){
            return redirect('/patients_reports');
        }else{
        return redirect('/reports/end_of_the_day/'.$request->invoice_date.'/'.$request->clinic_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = InvoiceHeadModel::where('id','=',$id)->update([
            'is_deleted' => 1,
            ]);
            if($delete){
                return 1;
            }
          //  return redirect()->route('patient_checkout_form.index');
    }

    public function TotalInsurancePayment($invoice_head_id)
    {
        $p_id = explode(',', $invoice_head_id); 
        //dd($p_id);
       $insurance_payment = DB::table("invoice_details")->whereIn('invoice_head_id',$p_id)->sum('insurance_payment');
       //print_r($insurance_payment); 
       return response()->json($insurance_payment);

    }
    public function CompletedStatus($id)
    {
       // dd($id);
        $data = InvoiceHeadModel::where('id','=',$id)->update([
            'is_completed' => '1'
        ]);
        if ($data) {
            return 1;
        }
    }


    public function FilterDayLocation(Request $request)
    {
        $reports = new DB();
        $saling_percentage[] = new DB();
        $date = $request->date;
        $clinic_id = $request->clinic_id;
        //dd($clinic_id);
       
       if ($clinic_id != '' && $date != '' ) {
           //dd("here");
         $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title SEPARATOR  ' \n ') AS payment_title FROM (
            SELECT base.*, d.quantity,  d.pro_unit_price,    
                        CONCAT('$', SUM(d.copayment)) AS total_amount,
                        CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment, GROUP_CONCAT(DISTINCT s.title SEPARATOR  ',') AS title FROM (
                                    SELECT
                                                h.id,
                                                (i.insurance_title) AS p_insurance_title,
                                                (si.insurance_title) AS s_insurance_title,                              
                                                h.invoice_date,
                                                u.name,
                                                CONCAT('$',CONVERT(h.insurance_balance,decimal(10,2))) AS insurance_balance,
                                                CONCAT('$',CONVERT(h.total_balance,decimal(10,2))) AS total_balance,
                                                h.is_completed,
                                                h.is_out_of_pocket,
                                                h.patient_firstname,
                                                h.patient_lastname,
                                                h.primary_insurance_id,  
                                                h.secondary_insurance_id,
                                                h.remarks
                                            FROM
                                            invoice_head h
                                            
                                            LEFT JOIN users u ON u.id = h.doctor_id
                                            LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                            LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                                        
                                            WHERE
                                                h.is_deleted = 0 AND h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."'
                                                GROUP BY h.id
                                              
                                    ) base
                                    LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                    LEFT JOIN services s ON s.id = d.service_id
                                    GROUP BY base.id
                                    ORDER BY base.id DESC ) AS result
                                    LEFT JOIN invoice_payments ip ON ip.invoice_head_id = result.id
                                    LEFT JOIN payment_methods p ON p.id = ip.payment_method_id                   
                                    GROUP BY result.id
                                    ORDER BY result.id DESC
            ");

                                            
                                     $yesterday_balance = DB::select("SELECT
                                            c.*,
                                            c.opening_balance AS yesterday_balance,
                                            c.cash_till_date AS invoice_date
                                        FROM
                                            cash_till c
                                        JOIN invoice_head h ON
                                            h.clinic_id = c.clinic_id
                                        WHERE  c.clinic_id = '".$clinic_id."' AND c.cash_till_date = '".$date."'
                                        GROUP BY c.cash_till_date ");
                                        //dd($yesterday_balance);

                                        if($yesterday_balance == NULL){
                                            $yesterday_balance = DB::select("SELECT
                                                0 AS any_refunds,
                                                0 AS extra_money_added,
                                                0 AS given_money,
                                                (SELECT SUM(ip.amount) FROM invoice_head ih 
                                        JOIN invoice_payments ip ON
                                            ip.invoice_head_id = ih.id WHERE  ih.invoice_date = '".$date."' AND ih.clinic_id = '".$clinic_id."'  AND h.is_deleted = 0 AND ip.payment_method_id = 2 ) AS cash_received_today,
                                                c.end_balance AS yesterday_balance,
                                                c.cash_till_date AS invoice_date
                                            FROM
                                                cash_till c
                                            JOIN invoice_head h ON
                                                h.clinic_id = c.clinic_id
                                            WHERE  c.clinic_id = '".$clinic_id."'  AND c.cash_till_date < '".$date."'
                                            ORDER BY c.cash_till_date  desc LIMIT 1");
                                        }
                                      //dd($yesterday_balance);
                                       $today_balance = DB::select("SELECT
                                            h.invoice_date,
                                          CONVERT(IFNULL((p.amount),0),decimal(10,2)) AS today_balance
                                        FROM
                                        invoice_head h 
                                        JOIN invoice_payments p ON
                                            p.invoice_head_id = h.id
                                        WHERE
                                            h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."' 
                                            AND h.is_deleted = 0 AND p.payment_method_id = 2
                                        ");
                
                //dd($saling_percentage);
                $saling_percentage = DB::select("SELECT base.*, CONCAT(CONVERT(base.fundus_ratio + base.medical_ratio,decimal(10,2)),'%') AS fun_medical_ratio FROM(
                    SELECT
                            COUNT(DISTINCT h.id) AS total_patients,
                            h.clinic_id,
                            CONCAT(22,'%') AS patient_target,
CONCAT(CONVERT( SUM(
                            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
                            END
                            )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS fundus_ratio,
                            CONCAT(CONVERT( SUM(
                            CASE WHEN d.service_id = 3 THEN 1 ELSE 0
                            END
                            )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS medical_ratio, CONCAT(70,'%') AS medical_target,
                            CONCAT(CONVERT(SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS cl_ratio,  CONCAT(50,'%') AS cl_target,
                            SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END) AS cl,
                            SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END) AS oasis,
                            CONCAT(CONVERT( SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS oasis_ratio, CONCAT(20,'%') AS oasis_target,
                            CONCAT(20,'%') AS family_target,
                                (SELECT COUNT(ih.family_upsell ) FROM invoice_head ih WHERE ih.invoice_date = h.invoice_date AND ih.clinic_id = h.clinic_id AND ih.family_upsell = 1) AS family_upsell_count  
                            FROM
                            invoice_head h
                           LEFT JOIN invoice_details d ON
                            d.invoice_head_id = h.id
                            WHERE h.is_deleted = 0 AND h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."'
                        )
                        AS base");  
            //dd($yesterday_balance);
            // return view("Patient_Checkout.index")->with("reports",$reports);
       }
       else{
           //dd("1");
            $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title SEPARATOR  ' \n ') AS payment_title FROM (
                SELECT base.*, d.quantity,  d.pro_unit_price,    
                            CONCAT('$', SUM(d.copayment)) AS total_amount,
                            CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment, GROUP_CONCAT(DISTINCT s.title SEPARATOR  ',') AS title FROM (
                                        SELECT
                                                    h.id,
                                                    (i.insurance_title) AS p_insurance_title,
                                                    (si.insurance_title) AS s_insurance_title,                              
                                                    h.invoice_date,
                                                    u.name,
                                                     CONCAT('$',CONVERT(h.insurance_balance,decimal(10,2))) AS insurance_balance,
                                                    CONCAT('$',CONVERT(h.total_balance,decimal(10,2))) AS total_balance,
                                                    h.is_completed,
                                                    h.is_out_of_pocket,
                                                    h.patient_firstname,
                                                    h.patient_lastname,
                                                    h.primary_insurance_id,  
                                                    h.secondary_insurance_id,
                                                    h.remarks
                                                FROM
                                                invoice_head h
                                                
                                                LEFT JOIN users u ON u.id = h.doctor_id
                                                LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                                LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                                            
                                                WHERE
                                                    h.is_deleted = 0 AND h.invoice_date = '".$date."' OR h.clinic_id = '".$clinic_id."'
                                                    GROUP BY h.id
                                                  
                                        ) base
                                        LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                        LEFT JOIN services s ON s.id = d.service_id
                                        GROUP BY base.id
                                        ORDER BY base.id DESC ) AS result
                                        LEFT JOIN invoice_payments ip ON ip.invoice_head_id = result.id
                                        LEFT JOIN payment_methods p ON p.id = ip.payment_method_id                   
                                        GROUP BY result.id
                                        ORDER BY result.id DESC ");

                                        $yesterday_balance = DB::select("SELECT
                                            c.*,
                                            c.opening_balance AS yesterday_balance,
                                            c.cash_till_date AS invoice_date
                                        FROM
                                            cash_till c
                                        JOIN invoice_head h ON
                                            h.clinic_id = c.clinic_id
                                        WHERE  c.clinic_id = '".$clinic_id."' AND c.cash_till_date = '".$date."'
                                        GROUP BY c.cash_till_date ");
                                        //dd($yesterday_balance);

                                        if($yesterday_balance == NULL){
                                            $yesterday_balance = DB::select("SELECT
                                                0 AS any_refunds,
                                                0 AS extra_money_added,
                                                0 AS given_money,
                                                (SELECT IFNULL(SUM(ip.amount),0) FROM invoice_head ih 
                                        JOIN invoice_payments ip ON
                                            ip.invoice_head_id = ih.id WHERE  ih.invoice_date = '".$date."' AND ih.clinic_id = '".$clinic_id."'  AND h.is_deleted = 0 AND ip.payment_method_id = 2 ) AS cash_received_today,
                                                c.end_balance AS yesterday_balance,
                                                c.cash_till_date AS invoice_date
                                            FROM
                                                cash_till c
                                            JOIN invoice_head h ON
                                                h.clinic_id = c.clinic_id
                                            WHERE  c.clinic_id = '".$clinic_id."'  AND c.cash_till_date < '".$date."'
                                            ORDER BY c.cash_till_date  desc LIMIT 1");
                                        }
                                      //dd($yesterday_balance);
                                    $today_balance = DB::select("SELECT
                                            h.invoice_date,
                                          CONVERT(IFNULL((p.amount),0),decimal(10,2)) AS today_balance
                                        FROM
                                        invoice_head h 
                                        JOIN invoice_payments p ON
                                            p.invoice_head_id = h.id
                                        WHERE
                                            h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."' 
                                            AND h.is_deleted = 0 AND p.payment_method_id = 2
                                        ");
                // $saling_percentage[] = new DB();
               // dd($yesterday_balance);
                $saling_percentage = DB::select("SELECT base.*, CONCAT(CONVERT(base.fundus_ratio + base.medical_ratio,decimal(10,2)),'%') AS fun_medical_ratio FROM(
                    SELECT
                            COUNT(DISTINCT h.id) AS total_patients,
                            h.clinic_id,
                            CONCAT(22,'%') AS patient_target,
CONCAT(CONVERT( SUM(
                            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
                            END
                            )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS fundus_ratio,
                            CONCAT(CONVERT( SUM(
                            CASE WHEN d.service_id = 3 THEN 1 ELSE 0
                            END
                            )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS medical_ratio, CONCAT(70,'%') AS medical_target,
                            CONCAT(CONVERT(SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS cl_ratio,  CONCAT(50,'%') AS cl_target,
                            SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END) AS cl,
                            SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END) AS oasis,
                            CONCAT(CONVERT( SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS oasis_ratio, CONCAT(20,'%') AS oasis_target,
                            CONCAT(20,'%') AS family_target,
                                (SELECT COUNT(ih.family_upsell ) FROM invoice_head ih WHERE ih.invoice_date = h.invoice_date AND ih.clinic_id = h.clinic_id AND ih.family_upsell = 1) AS family_upsell_count  
                            FROM
                            invoice_head h
                           LEFT JOIN invoice_details d ON
                            d.invoice_head_id = h.id
                            WHERE h.is_deleted = 0 AND h.invoice_date = '".$date."' AND h.clinic_id = '".$clinic_id."'
                        )
                        AS base");  
                //dd($yesterday_balance);
}
                    $end_day = DB::select("SELECT EXISTS(SELECT * FROM cash_till WHERE cash_till_date = '".$date."') AS exist_value");
                    return view('Patient_Checkout.index',compact('yesterday_balance','reports','today_balance','saling_percentage','end_day'));

    }

    public function InsuranceDateFilter(Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $reports = DB::select("SELECT result.*,  SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
            SELECT base.*, d.quantity,  d.pro_unit_price,                
            CONCAT('$', SUM(d.copayment)) AS total_amount,
                    CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment, GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                                SELECT
                                            h.id,
            
                                            (i.insurance_title) AS p_insurance_title,
                                            (si.insurance_title) AS s_insurance_title,                              
                                            h.invoice_date,
                                            u.name,
                                            c.location,
                                            h.is_completed,
                                            h.claim_status,
                                            h.patient_firstname,
                                            h.patient_lastname,
                                            h.primary_insurance_id,  
                                            h.secondary_insurance_id,
                                            h.remarks
                                        FROM
                                        invoice_head h
                                        
                                        LEFT JOIN users u ON u.id = h.doctor_id
                                        LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                        LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                                        LEFT JOIN clinics c ON c.id = h.clinic_id
                                        WHERE
                                           h.is_deleted = 0 AND h.is_out_of_pocket = 0 AND h.invoice_date >= '".$from_date."' AND h.invoice_date <= '".$to_date."'
                                   
                                            GROUP BY h.id
                                            ORDER By h.id desc
                                ) base
                                LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                LEFT JOIN services s ON s.id = d.service_id
                                GROUP BY base.id
                                ORDER BY base.id DESC 
                                ) AS result
                                 LEFT JOIN invoice_payments ip ON ip.invoice_head_id = result.id
                                LEFT JOIN payment_methods p ON p.id = ip.payment_method_id
                                GROUP BY result.id
                                ORDER BY result.id DESC ");
            return view('Patient_Checkout.index',compact('reports'));

    }
    public function InCompletedStatus($id)
    {
       // dd($id);
       $data =  InvoiceHeadModel::where('id','=',$id)->update([
            'is_completed' => '0'
        ]);
        if ($data) {
            return 1;
        }
    }
    public function Insurance($filter)
    {
        $reports = new DB();
        $total_patients = new DB();
        if ($filter == 'insurance_payments') {
            $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
                SELECT base.*,  d.quantity,  d.pro_unit_price,                                    CONCAT('$', SUM(d.copayment)) AS total_amount,
                            CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment, GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                                        SELECT
                                                    h.id,
                                                    h.is_completed,
                                                    (i.insurance_title) AS p_insurance_title,
                                                    (si.insurance_title) AS s_insurance_title,                              
                                                    h.invoice_date,
                                                    u.name,
                                                    h.claim_status,
                                                    CONCAT('$',CONVERT(h.insurance_balance,decimal(10,2))) AS insurance_balance,
                                                    CONCAT('$',CONVERT(h.total_balance,decimal(10,2))) AS total_balance,
                                                    c.location,
                                                    h.patient_firstname,
                                                    h.patient_lastname,
                                                    h.primary_insurance_id,  
                                                    h.secondary_insurance_id,
                                                    h.remarks
                                                FROM
                                                invoice_head h
                                                
                                                LEFT JOIN users u ON u.id = h.doctor_id
                                                LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                                LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                                                LEFT JOIN clinics c ON c.id = h.clinic_id
                                                WHERE
                                                  h.is_deleted = 0 AND h.is_out_of_pocket = 0
                                           
                                                    GROUP BY h.id
                                                    ORDER By h.id desc
                                        ) base
                                        LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                        LEFT JOIN services s ON s.id = d.service_id
                                       
                                        GROUP BY base.id
                                        ORDER BY base.id DESC 
                    ) AS result
                                         LEFT JOIN invoice_payments ip ON ip.invoice_head_id = result.id
                                        LEFT JOIN payment_methods p ON p.id = ip.payment_method_id
                                        GROUP BY result.id
                                        ORDER BY result.id DESC 
        
        ");
            $total_patients = DB::select("SELECT COUNT(id) AS overall_total FROM invoice_head");
            return view('Patient_Checkout.index',compact('reports','total_patients'));
    }
    }
    public function ClaimStatus(Request $request, $id)
    {
        $data =  InvoiceHeadModel::where('id','=',$id)->update([
            'claim_status' => $request->claim_status
        ]);
    }
}
