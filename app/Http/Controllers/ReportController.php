<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class ReportController extends Controller
{
    public function index()
    {
        $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
            SELECT base.*,  d.quantity, d.pro_unit_price,
                    CONCAT('$', SUM(d.copayment)) AS total_amount,
                 CONCAT('$',SUM(CASE WHEN year(base.invoice_date) = year(d.insurance_payment_entered_at) THEN d.insurance_payment ELSE 0 END)) AS insurance_payment,
                 CONCAT('$',SUM(CASE WHEN year(base.invoice_date) <> year(d.insurance_payment_entered_at) THEN d.insurance_payment ELSE 0 END)) AS next_year_payment,    						
                 GROUP_CONCAT(DISTINCT s.title) AS title  FROM (
                            SELECT
                                h.id,
                                (i.insurance_title) AS p_insurance_title,
                                (si.insurance_title) AS s_insurance_title,                              
                                h.invoice_date,
                                u.name,
                                c.location,
                                h.is_completed,
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
                                h.is_deleted = 0 AND year(h.invoice_date) = YEAR(now())
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

         return view('Reports.Yearly_Text_Report',compact('reports'));
    }
    public function CashFlow()
    {
        $reports = DB::select("SELECT
        c.*,
            u.name,
           c.clinic_id,
          l.location
        FROM
            cash_till c
     
       JOIN users u ON
            c.manager_id = u.id
        JOIN clinics l ON
            c.clinic_id = l.id
            GROUP BY 
                c.cash_till_date
             ORDER BY
            c.id
        DESC
            ");
        return view('Reports.cash_flow_report',compact('reports'));
    }
    public function DateFilter(Request $request)
    {
       
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
            SELECT base.*,  d.pro_unit_price,
                                    CONCAT('$', SUM(d.copayment)) AS total_amount,
                                    CONCAT('$',SUM(CASE WHEN year(base.invoice_date) = year(d.insurance_payment_entered_at) THEN d.insurance_payment ELSE 0 END)) AS insurance_payment,
                                    CONCAT('$',SUM(CASE WHEN year(base.invoice_date) <> year(d.insurance_payment_entered_at) THEN d.insurance_payment ELSE 0 END)) AS next_year_payment,    						
                                    GROUP_CONCAT(DISTINCT s.title) AS title, d.quantity FROM (
                            SELECT
                                    h.id,
                                
                                    h.is_completed,
            
                                    (i.insurance_title) AS p_insurance_title,
                                    (si.insurance_title) AS s_insurance_title,                              
                                    h.invoice_date,
                                    u.name,
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
                                    h.is_deleted = 0 AND h.invoice_date >= '".$from_date."' AND h.invoice_date <= '".$to_date."'
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

         return view('Reports.Yearly_Text_Report',compact('reports'));
    }
    public function AllPatientsReport()
    {
        $reports = DB::select("SELECT result.*,SUM(ip.amount) AS ip_total,
        GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM (
SELECT
        base.*,
        
        d.quantity,
        d.pro_unit_price,
        CONCAT('$', SUM(d.copayment)) AS total_amount,
        CONCAT('$', SUM(d.insurance_payment)) AS insurance_payment,
        GROUP_CONCAT(DISTINCT s.title) AS title
    FROM
        (
        SELECT
            h.id,
            h.invoice_date,
            WEEK(h.invoice_date) AS week_number,
            YEAR(h.invoice_date) AS year,
            h.is_completed,
            (i.insurance_title) AS p_insurance_title,
            (si.insurance_title) AS s_insurance_title,
            u.name,
            c.location,
            h.patient_firstname,
            h.patient_lastname,
            h.primary_insurance_id,
            h.secondary_insurance_id,
            h.remarks
        FROM
            invoice_head h
        LEFT JOIN users u ON
            u.id = h.doctor_id
        LEFT JOIN insurances i ON
            i.id = h.primary_insurance_id
        LEFT JOIN insurances si ON
            si.id = h.secondary_insurance_id
         LEFT JOIN clinics c ON c.id = h.clinic_id
        WHERE
            h.is_deleted = 0 AND h.invoice_date = CURRENT_DATE()
        GROUP BY
            h.id
        ORDER BY
            h.id
        DESC
            ) base
        LEFT JOIN invoice_details d ON
            d.invoice_head_id = base.id
        LEFT JOIN services s ON
            s.id = d.service_id
      
        GROUP BY
            base.id
        ORDER BY
            base.id
        DESC
            ) AS result
             LEFT JOIN invoice_payments ip ON
            ip.invoice_head_id = result.id
        LEFT JOIN payment_methods p ON
            p.id = ip.payment_method_id
            GROUP BY
            result.id
        ORDER BY
            result.id
            ");
            return view('Reports.Patients_Report',compact('reports'));
    }
    public function DateFilterAllPatient(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $reports = DB::select("SELECT result.*, SUM(ip.amount) AS ip_total,
        GROUP_CONCAT(DISTINCT p.title) AS payment_title from (
SELECT
        base.*,
        
        d.quantity,
        d.pro_unit_price,
        CONCAT('$', SUM(d.copayment)) AS total_amount,
        CONCAT('$', SUM(d.insurance_payment)) AS insurance_payment,
    CONCAT(
        '$',
        SUM(
            CASE WHEN YEAR(base.invoice_date) <> YEAR(d.insurance_payment_entered_at) THEN d.insurance_payment ELSE 0
        END
    )
    ) AS next_year_payment,
    GROUP_CONCAT(DISTINCT s.title) AS title
    FROM
        (
        SELECT
            h.id,
            WEEK(h.invoice_date) AS week_number,
            YEAR(h.invoice_date) AS year,
            (i.insurance_title) AS p_insurance_title,
            (si.insurance_title) AS s_insurance_title,
            h.invoice_date,
            h.is_completed,
            u.name,
            c.location,
            h.patient_firstname,
            h.patient_lastname,
            h.primary_insurance_id,
            h.secondary_insurance_id,
            h.remarks
        FROM
            invoice_head h
        LEFT JOIN users u ON
            u.id = h.doctor_id
        LEFT JOIN insurances i ON
            i.id = h.primary_insurance_id
        LEFT JOIN insurances si ON
            si.id = h.secondary_insurance_id
        LEFT JOIN clinics c ON c.id = h.clinic_id
        WHERE
            h.is_deleted = 0 AND h.invoice_date >= '".$from_date."' AND h.invoice_date <= '".$to_date."'
        GROUP BY
            h.id
        ORDER BY
            h.id
        DESC
    ) base
    LEFT JOIN invoice_details d ON
        d.invoice_head_id = base.id
    LEFT JOIN services s ON
        s.id = d.service_id
   
    GROUP BY
        base.id
    ORDER BY
        base.id
    DESC
            ) AS result
             LEFT JOIN invoice_payments ip ON
        ip.invoice_head_id = result.id
    LEFT JOIN payment_methods p ON
        p.id = ip.payment_method_id
        GROUP BY
        result.id
    ORDER BY
    result.id DESC
                        ");

         return view('Reports.Patients_Report',compact('reports'));
    }
    public function TransactionReports($filters)
    {
        $reports = new DB();
        if ($filters == 'completed') {
            $reports = DB::select("SELECT r.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM ( 
                SELECT base.*,   d.pro_unit_price,
                            CONCAT('$', SUM(d.copayment)) AS total_amount,
                         CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,
                         GROUP_CONCAT(DISTINCT s.title) AS title,  d.quantity FROM (
                                SELECT
                                        h.id,
                                        
                                        h.invoice_date,
                                        h.is_completed,
                                        week(h.invoice_date) AS week_number,
                                        year(h.invoice_date) AS year,
                                    
                                        (i.insurance_title) AS p_insurance_title,
                                            (si.insurance_title) AS s_insurance_title,                              
                                        c.location,
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
                                    WHERE
                                        h.is_deleted = 0 AND h.is_completed= 1
                                        GROUP BY h.id
                                        ORDER By h.id desc
                                ) base
                                LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                    LEFT JOIN services s ON s.id = d.service_id 
                               
                                GROUP BY base.id
                                ORDER BY base.id DESC 
                                ) AS r
                                 LEFT JOIN invoice_payments ip ON ip.invoice_head_id = r.id
                                LEFT JOIN payment_methods p ON p.id = ip.payment_method_id
                                 GROUP BY r.id
                                ORDER BY r.id DESC ");
                return view('Reports.Patients_Report',compact('reports'));
        }
        if ($filters == 'incompleted') {
            $reports = DB::select("SELECT r.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM ( 
                SELECT base.*,   d.pro_unit_price,
                            CONCAT('$', SUM(d.copayment)) AS total_amount,
                         CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,
                         GROUP_CONCAT(DISTINCT s.title) AS title,  d.quantity FROM (
                                SELECT
                                        h.id,
                                        
                                        h.invoice_date,
                                        h.is_completed,
                                        week(h.invoice_date) AS week_number,
                                        year(h.invoice_date) AS year,
                                    
                                        (i.insurance_title) AS p_insurance_title,
                                            (si.insurance_title) AS s_insurance_title,                              
                                        c.location,
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
                                    
                                    WHERE
                                        h.is_deleted = 0 AND h.is_completed= 0
                                        GROUP BY h.id
                                        ORDER By h.id desc
                                ) base
                                LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                    LEFT JOIN services s ON s.id = d.service_id 
                               
                                GROUP BY base.id
                                ORDER BY base.id DESC 
                                ) AS r
                                 LEFT JOIN invoice_payments ip ON ip.invoice_head_id = r.id
                                LEFT JOIN payment_methods p ON p.id = ip.payment_method_id
                                 GROUP BY r.id
                                ORDER BY r.id DESC ");    
                return view('Reports.Patients_Report',compact('reports'));
        }
        if ($filters == 'all') {
            $reports = DB::select("SELECT r.*, SUM(ip.amount) AS ip_total, GROUP_CONCAT(DISTINCT p.title) AS payment_title FROM ( 
                SELECT base.*,   d.pro_unit_price,
                            CONCAT('$', SUM(d.copayment)) AS total_amount,
                         CONCAT('$',SUM(d.insurance_payment)) AS insurance_payment,
                         GROUP_CONCAT(DISTINCT s.title) AS title,  d.quantity FROM (
                                SELECT
                                        h.id,
                                        
                                        h.invoice_date,
                                        h.is_completed,
                                        week(h.invoice_date) AS week_number,
                                        year(h.invoice_date) AS year,
                                    
                                        (i.insurance_title) AS p_insurance_title,
                                            (si.insurance_title) AS s_insurance_title,                              
                                    
                                        u.name,
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
                                        h.is_deleted = 0 
                                        GROUP BY h.id
                                        ORDER By h.id desc
                                ) base
                                LEFT JOIN invoice_details d ON d.invoice_head_id =  base.id 
                                    LEFT JOIN services s ON s.id = d.service_id 
                               
                                GROUP BY base.id
                                ORDER BY base.id DESC 
                                ) AS r
                                 LEFT JOIN invoice_payments ip ON ip.invoice_head_id = r.id
                                LEFT JOIN payment_methods p ON p.id = ip.payment_method_id
                                 GROUP BY r.id
                                ORDER BY r.id DESC ");
                return view('Reports.Patients_Report',compact('reports'));
        }
    }
}
