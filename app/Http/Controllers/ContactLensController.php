<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\ContactLensModel;
use App\Models\ContactLensTrackerModel;
class ContactLensController extends Controller
{
    public function index()
    {
        $data = ContactLensModel::where('is_deleted','=',0)->get();
        return view('Contact_Lens.index',compact('data'));
    }
    public function destroy($id)
    {
        //dd($id);
        $delete =  ContactLensModel::where('id','=',$id)->update([
            'is_deleted' => 1
        ]);
        if ($delete) {
            return 1;
        }
    }
    public function create($id)
    {
        $data = new ContactLensModel();
        if ($id > 0) {
            $data = ContactLensModel::find($id);
        }
        return view('Contact_Lens.forms',compact('data'));
    }
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        ContactLensModel::create([
            'contact_lens_name' => $request->contact_lens_name,
            'created_by' => $user_id
        ]);
        return redirect('/contact_lens');
    }
    public function update(Request $request,$id)
    {
        $user_id = Auth::user()->id;
        ContactLensModel::where('id','=',$id)->update([
            'contact_lens_name' => $request->contact_lens_name,
            'updated_by' => $user_id
        ]);
        return redirect('/contact_lens');
    }
    public function ContactLensTracker()
    {
        $data = DB::select('SELECT
        c.contact_lens_name,
                h.*,
                d.quantity,
                d.pro_unit_price,
                cl.location,
                s.status AS contact_lens_status
            FROM
                contact_lens c
            JOIN contact_lens_tracker l ON
                l.contact_lens_id = c.id
            JOIN invoice_head h ON
                h.id = l.invoice_head_id
            JOIN invoice_details d ON
                l.contact_lens_id = d.contact_lens_id
            JOIN contact_lens_status s ON
                s.id = l.lens_status_id
            JOIN clinics cl ON h.clinic_id = cl.id 
            WHERE h.is_deleted = 0
            GROUP BY l.invoice_head_id');
        return view('Contact_Lens_Tracker.index',compact('data'));
    }
    public function StatusUpdate(Request $request,$id)
    {
        ContactLensTrackerModel::where('invoice_head_id','=',$id)->update([
            'lens_status_id' => $request->contact_lens_status
        ]);
    }
}
