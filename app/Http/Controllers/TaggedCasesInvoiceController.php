<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class TaggedCasesInvoiceController extends Controller
{
    public function index($filters)
    {
        $reports = new DB();
        if ($filters == 'pending') {
            $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM ( 
                SELECT base.*,  d.quantity, CONCAT('$', d.pro_unit_price) AS unit_price,
                                                CONCAT('$',SUM(d.copayment)) as copayment,
                                                CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,  GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                                    SELECT
                                                h.id,
                                                h.is_completed,
                                                h.is_out_of_pocket,
                                                b.billing_company_invoice_id,
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
                                                    h.is_deleted = 0 AND h.is_completed = 0 AND h.is_out_of_pocket = 0 AND NOT EXISTS  (SELECT * FROM billing_company_invoices_details b WHERE b.invoice_head_id = h.id)
                
                                                GROUP BY h.id,i.id
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
        }
        if ($filters == 'completed') {
                                $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM ( 
                                    SELECT base.*,  d.quantity, CONCAT('$', d.pro_unit_price) AS unit_price,
                                                                    CONCAT('$',SUM(d.copayment)) as copayment,
                                                                    CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,  GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                                                        SELECT
                                                                    h.id,
                                                                    h.is_completed,
                                                                    h.is_out_of_pocket,
                                                                    b.billing_company_invoice_id,
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
                                                                                            h.is_deleted = 0 AND h.is_completed = 1 AND h.is_out_of_pocket = 0 AND NOT EXISTS  (SELECT * FROM billing_company_invoices_details b WHERE b.invoice_head_id = h.id)
                                    
                                                                    GROUP BY h.id,i.id
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
               // dd($reports);

        }
        if ($filters == 'all') {
            $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM ( 
                SELECT base.*,  d.quantity, CONCAT('$', d.pro_unit_price) AS unit_price,
                                                CONCAT('$',SUM(d.copayment)) as copayment,
                                                CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,  GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                                    SELECT
                                                h.id,
                                                h.is_completed,
                                                h.is_out_of_pocket,
                                                b.billing_company_invoice_id,
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
                                                                        h.is_deleted = 0  AND h.is_out_of_pocket = 0 AND NOT EXISTS  (SELECT * FROM billing_company_invoices_details b WHERE b.invoice_head_id = h.id)
                
                                                GROUP BY h.id,i.id
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
        }
        if ($filters == 'invoiced') {

            $reports = DB::select("SELECT result.*,SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
                SELECT base.*,  d.quantity, CONCAT('$', d.pro_unit_price) AS unit_price,
                            CONCAT('$',SUM(d.copayment)) as total_amount,
                            CONCAT('$',SUM(d.copayment)) as copayment,
                            CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,
                            GROUP_CONCAT(DISTINCT s.title) AS title FROM (
                SELECT
                            h.id,
                            b.billing_company_invoice_id,
                           
                            h.is_completed,
                            h.is_out_of_pocket,
                           
                            (i.insurance_title) AS p_insurance_title,
                                   (si.insurance_title) AS s_insurance_title,  
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
                               LEFT JOIN insurances i ON i.id = h.primary_insurance_id 
                            LEFT JOIN insurances si ON si.id = h.secondary_insurance_id 
                        LEFT JOIN clinics c ON c.id = h.clinic_id
                       LEFT JOIN tagged_invoices t ON t.invoice_head_id = h.id
                       
                        LEFT JOIN billing_company_invoices_details b ON b.invoice_head_id = h.id
                        LEFT JOIN billing_company_invoices bi ON bi.id = b.billing_company_invoice_id
                        WHERE
                            h.is_deleted = 0  AND h.is_out_of_pocket = 0 AND EXISTS  (SELECT * FROM billing_company_invoices_details b WHERE b.invoice_head_id = h.id)
                            GROUP BY h.id,i.id
                            ORDER By h.id desc
                    ) base
                     LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                            LEFT JOIN services s ON s.id = d.service_id
                   
                GROUP BY base.id
                ORDER BY base.billing_company_invoice_id DESC
                ) AS result
                 LEFT   JOIN invoice_payments ip ON ip.invoice_head_id = result.id
                    LEFT  JOIN payment_methods p ON p.id = ip.payment_method_id
                    GROUP BY result.id
                ORDER BY result.billing_company_invoice_id DESC");
                //dd($reports);
        }

        return view('Patient_Checkout.index',compact('reports'));
    }
}
