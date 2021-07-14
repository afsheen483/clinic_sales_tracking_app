<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\BillingCompanyInvoiceModel;

class PatientInvoiceMenuController extends Controller
{
    public function index($filter)
    {
        if ($filter == 'all') {
            $invoice_data = DB::select("SELECT
            b.id AS billing_id,
            b.count_of_patients,
            b.is_approved,
            CONCAT(
                '$',
                b.insurance_collection_amount
            ) AS insurance_collection_amount,
            DATE(b.billing_invoice_date) AS billing_invoice_date,
            CONCAT(b.billing_company_percent, '%') AS billing_company_percent,
            b.invoice_file,
            b.invoice_title,
            CONCAT('$', b.invoice_amount) AS invoice_amount,
           GROUP_CONCAT( bd.invoice_head_id) AS invoice_head_id,
            bd.billing_company_invoice_id
        FROM
            `billing_company_invoices` b
        LEFT JOIN billing_company_invoices_details bd ON
            bd.billing_company_invoice_id = b.id
            GROUP by b.id");
            return view('Invoice_Menu.index',compact('invoice_data'));
        }

        if ($filter == 'pending') {
            $invoice_data = DB::select("SELECT
            b.id AS billing_id,
            b.count_of_patients,
            b.is_approved,
            CONCAT(
                '$',
                b.insurance_collection_amount
            ) AS insurance_collection_amount,
            DATE(b.billing_invoice_date) AS billing_invoice_date,
            CONCAT(b.billing_company_percent, '%') AS billing_company_percent,
            b.invoice_file,
            b.invoice_title,
            CONCAT('$', b.invoice_amount) AS invoice_amount,
           GROUP_CONCAT( bd.invoice_head_id) AS invoice_head_id,
            bd.billing_company_invoice_id
        FROM
            `billing_company_invoices` b
        LEFT JOIN billing_company_invoices_details bd ON
            bd.billing_company_invoice_id = b.id
            WHERE b.is_approved = 0
            GROUP by b.id
       
            ");
           // dd($invoice_data);
            return view('Invoice_Menu.index',compact('invoice_data'));
        }
        if ($filter == 'approved') {
            $invoice_data = DB::select("SELECT
            b.id AS billing_id,
            b.count_of_patients,
            b.is_approved,
            CONCAT(
                '$',
                b.insurance_collection_amount
            ) AS insurance_collection_amount,
            DATE(b.billing_invoice_date) AS billing_invoice_date,
            CONCAT(b.billing_company_percent, '%') AS billing_company_percent,
            b.invoice_file,
            b.invoice_title,
            CONCAT('$', b.invoice_amount) AS invoice_amount,
           GROUP_CONCAT( bd.invoice_head_id) AS invoice_head_id,
            bd.billing_company_invoice_id
        FROM
            `billing_company_invoices` b
        LEFT JOIN billing_company_invoices_details bd ON
            bd.billing_company_invoice_id = b.id
            WHERE b.is_approved = 1
            GROUP by b.id
       
            ");
            return view('Invoice_Menu.index',compact('invoice_data'));
        }
    }
        

    public function InvoiceDetails($id)
    {
        //dd($id);
        $array_id = explode(',',$id);
        //dd($array_id);
        $invoice_data_details = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
            SELECT base.*,   d.quantity,  d.pro_unit_price,
                    CONCAT('$', SUM(d.copayment)) AS total_amount,
                    CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,    GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                                SELECT
                                        h.id,
                                
                                    
                                    (i.insurance_title) AS p_insurance_title,
                                    (si.insurance_title) AS s_insurance_title,
                                    h.is_completed,
                                    c.location,
                                    h.invoice_date,
                                        u.name,
                                    
                                    
                                        h.patient_firstname,
                                        h.patient_lastname,
                                        h.primary_insurance_id,
                                        h.secondary_insurance_id,
                                        h.remarks
                                    FROM
                                    invoice_head h
                                    
                                    LEFT JOIN users u ON u.id = h.doctor_id
                                    LEFT JOIN clinics c ON c.id = h.clinic_id
                                    LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                                    LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                                
                                    WHERE
                                        h.is_deleted = 0 AND h.id IN ($id)
                                    
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

        return view('Invoice_Menu.detail',compact('invoice_data_details'));

    }

    public function StatusApprove($id){
        $user_id = Auth::user()->id;
      $status =  BillingCompanyInvoiceModel::where('id','=',$id)->update([
            'is_approved' => '1',
            'approved_by' =>$id
        ]);
        if($status){
            return 1;
        }
    }
}
