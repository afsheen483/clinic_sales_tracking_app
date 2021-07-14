<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class DoctorModuleController extends Controller
{
    public function index()
    {
        $doctor_module_data = DB::select("SELECT
        COUNT(DISTINCT h.id) AS total_patients,
        WEEK(h.invoice_date) as week_number,
        year(h.invoice_date) AS year,
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
     CONCAT('$',CONVERT(IFNULL(SUM(d.copayment),0),decimal(10,2))) AS copayment,
       SUM(d.insurance_payment) AS insurance,
     CONCAT('$',CONVERT(IFNULL(SUM(d.copayment)+ SUM(d.insurance_payment),0),decimal(10,2))) AS final_amount
    FROM
    	invoice_head h 
    	LEFT JOIN  invoice_details d ON h.id = d.invoice_head_id
  
        WHERE 
        h.is_deleted = 0
    GROUP BY
        WEEK(h.invoice_date), year(h.invoice_date)");
    return view('Doctor_Module.index',compact('doctor_module_data'));
    }
    public function selectDoctor(Request $request)
    {
        $doctor_id = $request->doctor_id;
        $doctor_module_data = new DB();
        $doctor_module_data = DB::select("SELECT
        COUNT(DISTINCT h.id) AS total_patients,
        WEEK(h.invoice_date) as week_number,
        year(h.invoice_date) AS year,
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
      CONCAT('$',CONVERT(IFNULL(SUM(d.copayment),0),decimal(10,2))) AS copayment,
       SUM(d.insurance_payment) AS insurance,
     CONCAT('$',CONVERT(IFNULL(SUM(d.copayment)+ SUM(d.insurance_payment),0),decimal(10,2))) AS final_amount
    FROM
    invoice_head h 
    	LEFT JOIN  invoice_details d ON h.id = d.invoice_head_id
  
        WHERE h.is_deleted = 0 AND h.doctor_id = '".$doctor_id."' 
    GROUP BY
        WEEK(h.invoice_date), year(h.invoice_date)");

        if ($doctor_id == 'all') {
            $doctor_module_data = DB::select("SELECT
            COUNT(DISTINCT h.id) AS total_patients,
            WEEK(h.invoice_date) as week_number,
            year(h.invoice_date) AS year,
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
          CONCAT('$',CONVERT(IFNULL(SUM(d.copayment),0),decimal(10,2))) AS copayment,
       SUM(d.insurance_payment) AS insurance,
     CONCAT('$',CONVERT(IFNULL(SUM(d.copayment)+ SUM(d.insurance_payment),0),decimal(10,2))) AS final_amount
        FROM
        invoice_head h 
    	LEFT JOIN  invoice_details d ON h.id = d.invoice_head_id
  
            WHERE h.is_deleted = 0
        GROUP BY
            WEEK(h.invoice_date), year(h.invoice_date)");
        }
    return view('Doctor_Module.index',compact('doctor_module_data'));
    }
}
