<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    if (Auth::check()) {
        return view('admin.dashboard');
    }
    return view('newlogin');
})->name('login');

// dashboard route
Route::get('dashboard',function(){
    return view('admin.dasboard');
});


Route::get('/login', function () {
    if (Auth::check()) {
        return view('admin.dashboard');
    }
    return view('newlogin');
})->name('login');


Auth::routes();


Route::resource('users', 'UserController');

Route::resource('roles', 'RoleController');

Route::resource('permissions', 'PermissionController');

Route::resource('doctors', 'DoctorController');

Auth::routes();

// check status

Route::put('active_status_user/{id}','UserController@updatestatus')->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/permission_edit/{id}', 'PermissionController@edit')->middleware('auth');
Route::get('/roles_edit/{id}', 'RoleController@edit')->middleware('auth');
Route::get('/users_edit/{id}', 'UserController@edit')->middleware('auth');
Route::get('/doctors_edit/{id}', 'DoctorController@edit')->middleware('auth');
Route::get('dashboard', 'AdminController@index')->middleware('auth');

//routes of insurance controller
Route::resource('/insurance', 'InsuranceController')->middleware('auth');
Route::put('/insurance_update/{id}', 'InsuranceController@update')->middleware('auth');
Route::get('/insurance_delete/{id}', 'InsuranceController@delete')->middleware('auth');


//routes for clinics
Route::resource('clinics','ClinicController')->middleware('auth');
Route::put('clinic_update/{id}','ClinicController@update')->middleware('auth');
Route::get('clinic_delete/{id}','ClinicController@destroy')->middleware('auth');


// routes for servies
Route::resource('service','ServiceController')->middleware('auth');
Route::put('service_update/{id}','ServiceController@update')->middleware('auth');
Route::get('service_delete/{id}','ServiceController@destroy')->middleware('auth');

// routes for invoice head & invoice detail controller
Route::get('/reports/{filter}','InvoicesController@index')->middleware('auth');
Route::resource('patient_checkout_form','InvoicesController')->middleware('auth');
Route::put('patient_update/{id}/{pre}','InvoicesController@update')->middleware('auth');
Route::put("patient_checkout_delete/{id}","InvoicesController@destroy")->middleware('auth');
Route::put("completed_status/{id}","InvoicesController@CompletedStatus")->middleware('auth');
Route::put("incompleted_status/{id}","InvoicesController@InCompletedStatus")->middleware('auth');
Route::get("end_of_day_filter","InvoicesController@FilterDayLocation")->middleware('auth');

// route for end of day report
Route::get('reports/{filter}/{date}/{clinic_id}','InvoicesController@index');
Route::get('report/{filter}','InvoicesController@Insurance');
// claim Status
Route::put('claim_status_update/{id}','InvoicesController@ClaimStatus');



// routes for payment methods controller
Route::resource('payments','PaymentMethodController')->middleware('auth');
Route::get('payments_delete','PaymentMethodController@destroy')->middleware('auth');
Route::put('payments_update','PaymentMethodController@update')->middleware('auth');

// route for getting total insurance payment
// ajax call
Route::get("insurance_payment/{invoice_head_id}","InvoicesController@TotalInsurancePayment")->middleware('auth');


// billing company invoice route--------
Route::resource("billing_company_invoice","BillingCompanyInvoiceController")->middleware('auth');

// accounts receiveable summary
Route::get('/accounts_receiveable_summary','AccountSummaryController@index')->middleware('auth');
Route::post('tagged_invoices','AccountSummaryController@TaggedInvoices')->middleware('auth');
Route::put('remarks_insert/{id}','AccountSummaryController@RemarksInsert')->middleware('auth');
// all patients report
Route::get('all_patients/{week_number}_{year}','AccountSummaryController@AllPatients')->middleware('auth');
Route::get('weekly_report/{week_number}_{year}','WeeklyReportController@index')->middleware('auth');
Route::get('year','AccountSummaryController@YearAccountSummary')->middleware('auth');

// reporting manager module
Route::get('reporting_manager_module','ReportingManagerModule@index')->middleware('auth');
Route::get('location_dropdown','ReportingManagerModule@ManagerModule')->middleware('auth');
Route::post('labor_hours_insert','ReportingManagerModule@LaborHours')->middleware('auth');
Route::post('doctor_days_insert','ReportingManagerModule@DoctorDays')->middleware('auth');


// doctor module report
Route::get('doctor_module','DoctorModuleController@index')->middleware('auth');
Route::get('doctor_dropdown','DoctorModuleController@selectDoctor')->middleware('auth');


// patient invoice menu
Route::get("invoice_menu/{filter}","PatientInvoiceMenuController@index")->middleware('auth');
Route::get("invoice_details/{id}","PatientInvoiceMenuController@InvoiceDetails")->middleware('auth');
Route::put("approve_status/{id}","PatientInvoiceMenuController@StatusApprove")->middleware('auth');


// tagged cases routes
Route::get('tagged_cases/{filters}', 'TaggedCasesInvoiceController@index')->middleware('auth');


// insurance date filter
Route::get('insurance_date', 'InvoicesController@InsuranceDateFilter')->middleware('auth');

// weekly report

Route::get('weekly_report','WeeklyReportController@index')->middleware('auth');

// cashTill routes

Route::post('cash_till','CashTillController@index')->middleware('auth');


// yearly text report

Route::get('yearly_tax_report','ReportController@index')->middleware('auth');
Route::get('date_filter','ReportController@DateFilter')->middleware('auth');
Route::get('cash_flow_report','ReportController@CashFlow')->middleware('auth');
Route::get('date_filter_all_patient','ReportController@DateFilterAllPatient')->middleware('auth');


// location dropdown value in session

Route::get('location','LocationSessionController@index')->middleware('auth');

// all patients reports sidebar
Route::get('patients_reports','ReportController@AllPatientsReport')->middleware('auth');




// patients report incompleted && completed 
Route::get('transaction_report/{filters}','ReportController@TransactionReports')->middleware('auth');


// Route for check email
Route::post('user/checkemail', 'UserController@userEmailCheck');
Route::post('edit/checkemail', 'UserController@editEmailCheck');


// Route for complaint tracker
Route::get('complaint_tracker','PatientComplaintTrackerController@index')->middleware('auth');
Route::put('track_status_update/{id}','PatientComplaintTrackerController@TrackStatus')->middleware('auth');
Route::get('complaint_create/{id}','PatientComplaintTrackerController@create')->middleware('auth');
Route::put('manager_initials/{id}','PatientComplaintTrackerController@ManagerInitials')->middleware('auth');
Route::put('manager_comments/{id}','PatientComplaintTrackerController@ManagerComments')->middleware('auth');
Route::put('complaint_delete/{id}','PatientComplaintTrackerController@destroy')->middleware('auth');
Route::post('complaint_store','PatientComplaintTrackerController@store')->middleware('auth');
Route::put('complaint_update/{id}','PatientComplaintTrackerController@update')->middleware('auth');

// Route for Contact lens
Route::get('contact_lens','ContactLensController@index')->middleware('auth');
Route::put('contact_lens_delete/{id}','ContactLensController@destroy')->middleware('auth');
Route::get('contact_lens_create/{id}','ContactLensController@create')->middleware('auth');
Route::post('contact_lens_store','ContactLensController@store')->middleware('auth');
Route::put('contact_lens_update/{id}','ContactLensController@update')->middleware('auth');

// Route for contact lens tracker
Route::get('contact_lens_tracker','ContactLensController@ContactLensTracker')->middleware('auth');
Route::put('lens_status_update/{id}','ContactLensController@StatusUpdate')->middleware('auth');