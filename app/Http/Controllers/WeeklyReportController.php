<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class WeeklyReportController extends Controller
{
    public function index($week_number,$year)
    {
      
        $reports = DB::select("SELECT
       
        WEEK(h.invoice_date) as week_number,
        year(h.invoice_date) AS year,
        w.labor_hours, CONCAT(120,'hrs') AS labor_target,
        
        w.doctor_days,
        CONCAT(CONVERT(w.labor_hours/w.doctor_days,decimal(10,2)),'hrs') AS labor_hours_per_day,CONCAT(16,'hrs/day') AS labor_per_day_target, 
        SUM(
            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
        END
    ) AS fundus,
    CONCAT(CONVERT( SUM(
            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
        END
    )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS fundus_ratio,CONCAT(70,'%') AS fundus_target,
    
    
      SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END) AS oasis,
      CONCAT(CONVERT( SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS oasis_ratio,CONCAT(15,'%') AS oasis_target,
      (SELECT COUNT(ih.family_upsell ) FROM invoice_head ih WHERE year(ih.invoice_date) = year(h.invoice_date) AND week(ih.invoice_date) = week(h.invoice_date) AND ih.family_upsell = 1) AS family_upsell_count ,CONCAT('5/day',' ','(20 a week)') AS upsell_target,
      (SELECT COUNT(inh.id)  FROM invoice_head inh  WHERE week(inh.invoice_date) = '".$week_number."' AND year(inh.invoice_date) = '".$year."' AND inh.is_deleted = 0)AS total_patients,CONCAT(160,' ','(20/day)') AS patient_target
    FROM
        invoice_details d
    JOIN invoice_head h ON
        d.invoice_head_id = h.id
    LEFT JOIN worked_days w ON w.week_no = week(h.invoice_date) AND w.year = year(h.invoice_date)
   WHERE week(h.invoice_date) = '".$week_number."' AND year(h.invoice_date) = '".$year."' AND h.is_deleted = 0
    GROUP BY
        WEEK(h.invoice_date), year(h.invoice_date)");
        return view('Week_Report.index',compact('reports'));
    }
    
}
