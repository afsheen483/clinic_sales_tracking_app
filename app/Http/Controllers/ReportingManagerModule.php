<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\WorkedDaysModel;
class ReportingManagerModule extends Controller
{
    public function index()
    {
        $manager_module_data = DB::select("SELECT
        COUNT(DISTINCT h.id) AS total_patients,
        WEEK(h.invoice_date) as week_number,
        year(h.invoice_date) AS year,
        w.labor_hours,
        w.doctor_days,
        SUM(
            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
        END
    ) AS fundus,
    CONCAT(CONVERT( SUM(
            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
        END
    )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS fundus_ratio,
     SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END) AS CL,
    CONCAT(CONVERT(SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS cl_ratio,
     SUM(CASE WHEN d.service_id = 3 THEN 1 ELSE 0 END) AS medical,
     CONCAT(CONVERT(SUM(CASE WHEN d.service_id = 3 THEN 1 ELSE 0 END)/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS medical_ratio,
      SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END) AS oasis,
      CONCAT(CONVERT( SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS oasis_ratio,
    
     (SELECT COUNT(ih.family_upsell ) FROM invoice_head ih WHERE year(ih.invoice_date) = year(h.invoice_date) AND week(ih.invoice_date) = week(h.invoice_date) AND ih.family_upsell = 1) AS family_upsell_count
    FROM
        invoice_head h 
    	LEFT JOIN  invoice_details d ON h.id = d.invoice_head_id
  
    LEFT JOIN worked_days w ON w.week_no = week(h.invoice_date) AND w.year = year(h.invoice_date)
    WHERE h.is_deleted = 0
    GROUP BY
        WEEK(h.invoice_date), year(h.invoice_date)");
    return view('Manager.index',compact('manager_module_data'));
    }
    public function ManagerModule(Request $request)
    {
        $clinic_id = $request->clinic_id;
        $manager_module_data = DB::select("SELECT
        COUNT(DISTINCT h.id) AS total_patients,
        WEEK(h.invoice_date) as week_number,
        year(h.invoice_date) AS year,
        w.labor_hours,
        w.doctor_days,
        SUM(
            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
        END
    ) AS fundus,
    CONCAT(CONVERT( SUM(
            CASE WHEN d.service_id = 4 THEN 1 ELSE 0
        END
    )/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS fundus_ratio,
     SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END) AS CL,
    CONCAT(CONVERT(SUM(CASE WHEN d.service_id = 2 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS cl_ratio,
     SUM(CASE WHEN d.service_id = 3 THEN 1 ELSE 0 END) AS medical,
     CONCAT(CONVERT(SUM(CASE WHEN d.service_id = 3 THEN 1 ELSE 0 END)/COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS medical_ratio,
      SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END) AS oasis,
      CONCAT(CONVERT( SUM(CASE WHEN d.service_id = 14 THEN 1 ELSE 0 END)/ COUNT(DISTINCT h.id) * 100,decimal(10,2)),'%') AS oasis_ratio,
    
     (SELECT COUNT(ih.family_upsell ) FROM invoice_head ih WHERE ih.invoice_date = h.invoice_date AND ih.clinic_id = h.clinic_id AND ih.family_upsell = 1) AS family_upsell_count  
    FROM
    invoice_head h 
    	LEFT JOIN  invoice_details d ON h.id = d.invoice_head_id
  
    LEFT JOIN worked_days w ON w.week_no = week(h.invoice_date) AND w.year = year(h.invoice_date)
    WHERE h.is_deleted = 0 AND h.clinic_id = '".$clinic_id."' 
    GROUP BY
        WEEK(h.invoice_date), year(h.invoice_date)");
    return view('Manager.index',compact('manager_module_data'));

    }
    public function LaborHours(Request $request)
    {
        $user_id = Auth::user()->id;
        $year_week = $request->year_week;
        $labor_hours_value = $request->labor_hours_value;
        $split_values = explode ("_", $year_week); 
       // dd($split_values[2]);

        $week_number = $split_values[1];
        $year = $split_values[2];

            WorkedDaysModel::updateOrCreate([
                    'year' => $year,
                    'week_no' => $week_number,
                ],[
                    'labor_hours' => $labor_hours_value,
                    'created_by' => $user_id
        ]);

    }

    public function DoctorDays(Request $request)
    {
        
        $user_id = Auth::user()->id;
        $year_week = $request->year_week;
        $doctor_days_value = $request->doctor_days_value;
        $split_values = explode ("_", $year_week); 
        $week_number = $split_values[1];
        $year = $split_values[2];

            WorkedDaysModel::updateOrCreate([
                    'year' => $year,
                    'week_no' => $week_number,
                ],[
                    'doctor_days' => $doctor_days_value,
                    'created_by' => $user_id
        ]);
    }
}
