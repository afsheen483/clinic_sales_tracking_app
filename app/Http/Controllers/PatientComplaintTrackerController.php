<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\PatientComplaintTrackerModel;

class PatientComplaintTrackerController extends Controller
{
    public function index()
    {
        $data = DB::select('SELECT
        p.*,
        c.location,
        t.status_type AS track_status,
        ps.priority_type AS priority_status
    FROM
        patient_complaint_tracker p
        LEFT JOIN clinics c ON c.id = p.location_id
        LEFT JOIN track_status t ON t.id = p.status_id
        LEFT JOIN priority_status ps ON ps.id = p.priority_id
    WHERE
        p.is_deleted = 0
    ORDER BY p.id DESC');
        return view('Complaint_Tracker.index',compact('data'));
    }
    public function TrackStatus(Request $request,$id)
    {
        PatientComplaintTrackerModel::where('id','=',$id)->update([
            'status_id' => $request->track_status
        ]);
    }
    public function create($id)
    {

        $data = new PatientComplaintTrackerModel();
        if ($id > 0) {
            $data = PatientComplaintTrackerModel::find($id);
            return view('Complaint_Tracker.form',compact('data'));
        }
        return view('Complaint_Tracker.form',compact('data'));

    }
    public function ManagerInitials(Request $request,$id){
        PatientComplaintTrackerModel::where('id','=',$id)->update([
            'manager_initials' => $request->val
        ]);
    }
    public function ManagerComments(Request $request,$id)
    {
        PatientComplaintTrackerModel::where('id','=',$id)->update([
            'comments_from_manager' => $request->val
        ]);
    }
    public function destroy($id)
    {
       $delete =  PatientComplaintTrackerModel::where('id','=',$id)->update([
            'is_deleted' => 1
        ]);
        if ($delete) {
            return 1;
        }
    }
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        PatientComplaintTrackerModel::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'staff_name' => $request->staff_name,
            'location_id' => $request->location_id,
            'date_of_complaint' => $request->date,
            'description' => $request->description,
            'priority_id' => $request->priority_id,
            'created_by' => $user_id,
            'status_id' => 1,
        ]);
        return redirect('/complaint_tracker');
    }
    public function update(Request $request,$id)
    {
        $user_id = Auth::user()->id;
        PatientComplaintTrackerModel::where('id','=',$id)->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'staff_name' => $request->staff_name,
            'location_id' => $request->location_id,
            'date_of_complaint' => $request->date,
            'description' => $request->description,
            'priority_id' => $request->priority_id,
            'updated_by' => $user_id,
        ]);
        return redirect('/complaint_tracker');
    }
}
