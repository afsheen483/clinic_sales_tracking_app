<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\TaggedInvoicesModel;
use App\Models\TaggedInvoiceDetailModel;
class AccountSummaryController extends Controller
{
    public function index()
    {
       $reports = DB::select("SELECT
       id,
        is_paid,
        total_patients,
        week_number,
        year,
        family_upsell_count,
        remarks,
        CONCAT('$', unpaid_count * 47) AS unpaid_amount,
        unpaid_count,
        CONCAT(
            CONVERT(
                IFNULL(
                    unpaid_count / total_patients * 100,
                    0
                ),
                DECIMAL(10, 2)
            ),
            '%'
        ) AS unpaid_percentage,
        is_completed
    FROM
        (
        SELECT
            
            h.id,
          GROUP_CONCAT( t.is_paid) AS is_paid,
            COUNT(h.id) total_patients,
            week(h.invoice_date) AS week_number,
            year(h.invoice_date) AS year,
            h.is_completed,
            t.remarks,
                SUM(
                    IFNULL(CASE WHEN IFNULL(h.is_completed,0) = 0 AND IFNULL(h.is_out_of_pocket,0) = 0 THEN 1 ELSE 0
                END,0)
           
    ) AS unpaid_count,
            (SELECT COUNT(ih.family_upsell ) FROM invoice_head ih WHERE year(ih.invoice_date) = year(h.invoice_date) AND week(ih.invoice_date) = week(h.invoice_date) AND ih.family_upsell = 1) AS family_upsell_count
            
    FROM
        invoice_head h 
            LEFT JOIN tagged_invoices t ON t.invoice_head_id = h.id
          WHERE h.is_deleted = 0
            GROUP BY week(h.invoice_date),year(h.invoice_date)
    ) p");

   $accounts_summary = DB::select("SELECT total_patients,CONCAT('$',unpaid_count * 47) AS unpaid_amount,CONCAT(CONVERT(IFNULL(unpaid_count/total_patients * 100, 0),decimal(10,2)),'%') AS unpaid_percentage, CONCAT(CONVERT(targets,decimal(10,2)),'%') AS unpaid_target, time_period FROM  (
    SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 40 AS targets, CONCAT(0,'-',30,' ','days')  AS time_period   
        FROM `invoice_head` WHERE (`invoice_date` >= CURRENT_DATE() - INTERVAL 30 DAY )
    UNION ALL 
    SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 20 AS targets, CONCAT(30,'-',45,' ','days')  AS time_period     
        FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 30 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 45 DAY)
    UNION ALL
       SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 15 AS targets, CONCAT(45,'-',60,' ','days')  AS time_period     
        FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 45 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 60 DAY)
    UNION ALL
        SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 8 AS targets,  CONCAT(60,'-',90,' ','days') AS time_period   
        FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 60 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 90 DAY)
    UNION ALL
    SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 6 AS targets,  CONCAT(90,'-',180,' ','days') AS time_period    
        FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 90 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 180 DAY)
        UNION ALL
    SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 1 AS targets,  CONCAT(180,'+',' ','days') AS time_period   
        FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 180 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 360 DAY)
     ) p
     ");
    return view('Accounts.index',compact('reports','accounts_summary'));

}

public function TaggedInvoices(Request $request)
{
    $user_id = Auth::user()->id;
    $date = date("Y-m-d h:i:s");
    $invoice_id = $request->id;
   
    $week_number = $request->week;
    $year_number = $request->year;
    //dd($week_number ." " .$year_number );

            $patient_data = DB::select("SELECT
                    h.id AS invoice_head_id,
                    WEEK(h.invoice_date),
                    YEAR(h.invoice_date),
                    CONCAT(
                        h.patient_firstname,
                        '',
                        h.patient_lastname
                    ) AS patient_name,
                    h.invoice_date,
                    h.remarks,
                    SUM(d.copayment) AS copayment
                FROM
                    invoice_head h
                JOIN invoice_details d ON
                    h.id = d.invoice_head_id
                WHERE
                    WEEK(h.invoice_date) = '".$week_number."' AND YEAR(h.invoice_date) = '".$year_number."' AND h.is_completed = 0
                GROUP BY
                    h.id");
             //dd($patient_data);
            $array_id = explode(',',$invoice_id);
            global $invoice_tagged;
           
            foreach ($patient_data as $data) {
                $invoice_tagged = TaggedInvoicesModel::updateOrCreate([
                    'invoice_head_id' => $data->invoice_head_id,
             ],[
                     'tagged_by' => $user_id,
                     'is_paid' => '1',
                     'marked_paid_by' => $user_id,
                     'marked_at' => $date,
                     'created_by' => $user_id,
                     'is_deleted' => '0'
                 ]);
            }
            if ($invoice_tagged) {
                return 1;
            }
}
public function RemarksInsert(Request $request)
{
    $invoice_id = $request->id;
    $week_number = $request->week;
    $year_number = $request->year;
    //dd($week_number ." " .$year_number );
    $patient_data = DB::select("SELECT
                    h.id AS invoice_head_id,
                    WEEK(h.invoice_date),
                    YEAR(h.invoice_date),
                    CONCAT(
                        h.patient_firstname,
                        '',
                        h.patient_lastname
                    ) AS patient_name,
                    h.invoice_date,
                    h.remarks,
                    SUM(d.copayment) AS copayment
                FROM
                    invoice_head h
                JOIN invoice_details d ON
                    h.id = d.invoice_head_id
                WHERE
                    WEEK(h.invoice_date) = '".$week_number."' AND YEAR(h.invoice_date) = '".$year_number."' AND h.is_completed = 0
                GROUP BY
                    h.id");
        //dd($patient_data);
    $array_id = explode(',',$invoice_id);
    global $comment;
    foreach ($patient_data  as $data) {
        $comment =  TaggedInvoicesModel::updateOrCreate([
            'invoice_head_id' => $data->invoice_head_id,
        ],[
                'remarks' => $request->remarks
            ]);
    }
   
    if ($comment) {
        return 1;
    }
}
    public function AllPatients($week_number,$year)
    {
       $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
        SELECT base.*,  d.quantity,  d.pro_unit_price,
                            CONCAT('$', SUM(d.copayment)) AS total_amount,
                            CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,
                            GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                        SELECT
                            h.id,
                            h.is_completed,
        
                            week(h.invoice_date) as week_number,
                            year(h.invoice_date) as year, 
                        
                            t.is_paid,
                            (i.insurance_title) AS p_insurance_title,
                            (si.insurance_title) AS s_insurance_title,                              
                            h.is_completed = 1  AS paid,
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
                        LEFT JOIN tagged_invoices t ON t.invoice_head_id = h.id
        
                        WHERE
                            h.is_deleted = 0 AND week(h.invoice_date) = '".$week_number."' AND year(h.invoice_date) = '".$year."' 
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
                        ORDER BY result.id DESC");
            return view("Reports.all_patients",compact('reports'));

    }
    public function YearAccountSummary(Request $request)
    {
        $year = $request->year;
        $reports = DB::select("SELECT
        id,
           is_paid,
         total_patients,
         week_number,
         year,
         family_upsell_count,
         remarks,
         CONCAT('$', unpaid_count * 47) AS unpaid_amount,
         unpaid_count,
         CONCAT(
             CONVERT(
                 IFNULL(
                     unpaid_count / total_patients * 100,
                     0
                 ),
                 DECIMAL(10, 2)
             ),
             '%'
         ) AS unpaid_percentage
     FROM
         (
         SELECT
             
             h.id,
           GROUP_CONCAT( t.is_paid) AS is_paid,
             COUNT(h.id) total_patients,
             week(h.invoice_date) AS week_number,
             year(h.invoice_date) AS year,
             t.remarks,
             
             IFNULL(
                 SUM(
                     CASE WHEN h.is_completed = 0 AND h.is_out_of_pocket = 0 THEN 1 ELSE 0
                 END
             ),
             0
     ) AS unpaid_count,
     SUM(CASE WHEN h.family_upsell = 1 THEN 1 ELSE 0 END) AS family_upsell_count
     FROM
         invoice_head h 
             LEFT JOIN tagged_invoices t ON t.invoice_head_id = h.id
               WHERE year(h.invoice_date) = '".$year."'
             GROUP BY week(h.invoice_date),year(h.invoice_date)
     ) p");
       $accounts_summary = DB::select("SELECT total_patients,CONCAT('$',unpaid_count * 47) AS unpaid_amount,CONCAT(CONVERT(IFNULL(unpaid_count/total_patients * 100, 0),decimal(10,2)),'%') AS unpaid_percentage, CONCAT(CONVERT(targets,decimal(10,2)),'%') AS unpaid_target, time_period FROM  (
        SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 40 AS targets, CONCAT(0,'-',30,' ','days')  AS time_period   
            FROM `invoice_head` WHERE (`invoice_date` >= CURRENT_DATE() - INTERVAL 30 DAY )
        UNION ALL 
        SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 20 AS targets, CONCAT(30,'-',45,' ','days')  AS time_period     
            FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 30 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 45 DAY)
        UNION ALL
           SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 15 AS targets, CONCAT(45,'-',60,' ','days')  AS time_period     
            FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 45 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 60 DAY)
        UNION ALL
            SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 8 AS targets,  CONCAT(60,'-',90,' ','days') AS time_period   
            FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 60 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 90 DAY)
        UNION ALL
        SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 6 AS targets,  CONCAT(90,'-',180,' ','days') AS time_period    
            FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 90 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 180 DAY)
            UNION ALL
        SELECT COUNT(id) total_patients, IFNULL(SUM(CASE WHEN is_completed = 0 AND is_out_of_pocket = 0 THEN 1 ELSE 0 END),0) AS unpaid_count, 1 AS targets,  CONCAT(180,'+',' ','days') AS time_period   
            FROM `invoice_head` WHERE (`invoice_date` <= CURRENT_DATE() - INTERVAL 180 DAY AND `invoice_date` >= CURRENT_DATE() - INTERVAL 360 DAY)
         ) p
         ");
        return view('Accounts.index',compact('reports','accounts_summary'));
    }
}
