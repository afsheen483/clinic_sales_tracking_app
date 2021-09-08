<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

<style>
    body {
     /* Moz-browsers */
    zoom: 0.8; /* Other non-webkit browsers */
    zoom: 80%; /* Webkit browsers */
}
/* .slimScrollDiv{
    overflow:  visible !important;
}
.sidebar-inner{
    overflow:  visible !important;
}
.slimScrollBar{
    overflow-y: scroll;
  height: auto !important;
  max-height: 10000px !important; 
} */
</style>

   
</head>

<body>

    <div class="main-wrapper">
        <div class="header">
			<div class="header-left">
				<a href="/dashboard" class="logo">
					<img src="{{ asset('assets/img/logo.png') }}" width="35" height="35" alt=""><span>Clinic Sales Tracking</span>
				</a>
			</div>
			<a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
               
            <ul class="nav user-menu float-right">

                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
							<img class="rounded-circle" src="{{ asset('assets/img/user.jpg') }}" width="24" alt="Admin">
							<span class="status online"></span>
						</span>
						<span>{{ Auth::user()->name }}</span>
                    </a>
					<div class="dropdown-menu">
						    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf   
                      </form>
					</div>
                </li>
            </ul>
           
            {{-- <div style="float: right; margin-top:0.1cm" class="col-1">
              
                <select class="form-control" name="clinic_id" id="location">
                   
                        @foreach ($clinic_array as $clinic)
                        @if (session()->get('location_name') == $clinic->id )
                        <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                        @else
                        <option value="{{$clinic->id}}">{{$clinic->location}}</option>
                        @endif
                        @endforeach
                </select>
            </div>
            <label for="" style="float: right;font-weight:bold;color:white;margin-top:0.3cm" > Location:</label> --}}
        </div>
        
    


        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li id="d">
                            <a href="/dashboard" id="click_btn"><i class="fa fa-dashboard"></i> <span id="dashboard">Dashboard</span></a>
                        </li>
                        @hasanyrole('Staff|Manager|admin|Accountant')
                        <li>
                            <a href="{{  route('patient_checkout_form.create',['id'=>'0']) }}"><i class="fal fa-sticky-note"></i> <span>Checkout Patient Form</span></a>
                        </li>
                        <li>
                            <a href="/reports/end_of_the_day/<?php echo date('Y-m-d')?>/1"><i class="fas fa-clipboard-list"></i> <span>End Of the Day</span></a>
                        </li>
                        <li>
                            <a href="/report/insurance_payments"><i class="fas fa-analytics"></i> <span>Insurance Payments</span></a>
                        </li>
                        @endhasanyrole
                        @hasanyrole('Manager|admin|Accountant')
                        <li >
                            <a href="/accounts_receiveable_summary"><i class="fa fa-list-alt" aria-hidden="true"></i><span>Accounts  Summary</span></a>
                        </li>
                       
                        @endhasanyrole
                       
                        @hasanyrole('Manager|admin|Accountant')
                        
                        @endhasanyrole
                        <li class="submenu">
							<a href="#"><i class="fas fa-clipboard-list-check"></i> <span> Billing Company </span> <span class="menu-arrow"></span></a>
							<ul style="display: none;">
                                {{-- <li>
                                    <a href="../reports/patient_checkout"><i class="fal fa-book-medical"></i> <span>Checkout Patient</span></a>
                                </li> --}}
                                <li>
                                    <a href="/tagged_cases/all"><i class="fas fa-money-bill-alt"></i>  <span> Tagged Cases</span></a>
                                </li>
                                <li>
                                    <a href="/invoice_menu/all"><i class="fas fa-file-invoice"></i> <span>Billing Company Invoice</span></a>
        
                                </li>
                                
                                @hasanyrole('Manager|admin|Staff')
                               
                               
                                @endhasanyrole
                                @hasanyrole('Manager|admin|Accountant')
                               
                                @endhasanyrole
                               
							</ul>

						</li>
                        @hasanyrole('Manager|admin')
                        {{-- <li >
                            <a href="/weekly_report"><i class="fa fa-tasks" aria-hidden="true"></i> <span>Weekly Report</span></a>
                        </li> --}}
                        <li >
                            <a href="/reporting_manager_module"><i class="fa fa-tasks" aria-hidden="true"></i>
                                <span>Manager Report</span></a>
                        </li>
                        @endhasanyrole
                        <li>
                            <a href="/complaint_tracker"><i class="fas fa-comments"></i><span>Patient Complaint Tracker</span></a>
                        </li>
                        <li>
                            <a href="/contact_lens_tracker"><i class="far fa-analytics"></i><span>Contact lens Tracker</span></a>
                        </li>
                        @hasrole('admin')
                        <li >
                            <a href="/doctor_module"><i class="fa fa-tasks" aria-hidden="true"></i> <span>Doctor Report</span></a>
                        </li>
                        
                        @endhasrole
                        <li >
                            <a href="/patients_reports"><i class="fa fa-tasks" aria-hidden="true"></i> <span>All Patients Report</span></a>
                        </li>
                        <li >
                            <a href="/cash_flow_report"><i class="fa fa-tasks" aria-hidden="true"></i> <span>Cash Flow Report</span></a>
                        </li>
            
                        <li >
                            <a href="/yearly_tax_report"><i class="fa fa-tasks" aria-hidden="true"></i> <span>Yearly Tax Report</span></a>
                        </li>
                       
					
                       
                        @hasrole('admin')
                        <li class="submenu">
							<a href="#"><i class="far fa-user"></i><span> User Management </span> <span class="menu-arrow"></span></a>
							<ul style="display: none;">
							<li>
                            <a href="/users"><i class="fa fa-user"></i><span>Users</span></a>
                            </li>
                            <li>
                                <a href="/doctors"><i class="fas fa-user-md"></i><span>Doctors</span></a>
                            </li>
    						<li>
                                <a href="/roles"><i class="fa fa-user"></i><span>Roles</span></a>
                            </li>
                            <li>
                            <a href="/permissions"><i class="fas fa-key"></i><span>Permissions</span></a>
                            </li>                            
                
							</ul>
						</li>
						
                        <li class="submenu">
							<a href="#"><i class="fas fa-cog"></i> <span> Settings </span> <span class="menu-arrow"></span></a>
							<ul style="display: none;">
                                <li><a href="/insurance"><i class="fas fa-analytics"></i>  <span> Insurances</span></a></li>
                                <li>
                                    <a href="/clinics"><i class="fas fa-clinic-medical"></i><span>Clinics</span></a>
                                </li>
                                <li>
                                    <a href="/contact_lens"><i class="fas fa-glasses"></i></i>
                                        <span>Contact Lens</span></a>
                                </li>
                                {{-- <li>
                                    <a href="../service"><i class="far fa-tasks"></i>
                                        <span>Services</span></a>
                                </li> --}}
                                 <li>
                                    <a href="/payments"><i class="fab fa-cc-amazon-pay"></i>
                                        <span>Payment Methods</span></a>
                                </li>
                                

                               
                                
                       
								
							</ul>
						</li>
                        @endhasrole
						<li>
						
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i><span>
                            {{ __('Logout') }}</span>
                            
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf   
                      </form>
                         
						</li>
						
						
                    </ul>
                </div>
            </div>
        </div>

						
						
						
                    </ul>
                </div>
           
     
        <div class="page-wrapper">
            <div class="content">
               @yield('content')
            </div>
            
        </div>
   
    <div class="sidebar-overlay" data-reff=""></div>
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


@yield('scripts')

<script>


    $(function(){
        $('#location option').each(function() {
            var location_name = $(this).val();
           
            if($(this).is(':selected')) 
            //alert(location_name);
            var url = "{{ url('location') }}";
            $.ajax({
                url: url,
                type: "GET",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    location_name:location_name,
                },
                success: function(data) {
                 console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
           });
    });
   
    // $('.sidebar li').on('mouseover click',function(e) {

    //     $('.sidebar li').removeClass('active');

    //     var $this = $(this);
    //     if (!$this.hasClass('active')) {
    //         $this.addClass('active');
    //     }
    //     //e.preventDefault();
    // });
    $(document).ready(function () {
        var url = window.location;
    // Will only work if string in href matches with location
        $('.sidebar a[href="' + url + '"]').parent().addClass('active');

    // Will also work for relative and absolute hrefs
        $('.sidebar a').filter(function () {
            return this.href == url;
        }).parent().addClass('active').parent().parent().addClass('active');

        $("#location").change(function(){
            var location_name = $(this).val();
            // alert(location_name);
            var url = "{{ url('location') }}";
            $.ajax({
                url: url,
                type: "GET",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    location_name:location_name,
                },
                success: function(data) {
                 console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
           });


           
        });
   

</script>
</body>


<!-- index22:59-->
</html>