@extends('layouts.master')
@if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true || Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


@endif
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  th { font-size: 14px; }
td { font-size: 13px; }
</style>
@section('title')
@if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
    End Of Day
 @endif 
 @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true)

    Insurance Payments
 @endif   
 @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' || Request::route('filters') == 'invoiced')
  Tagged Cases
 @endif
@endsection

@section('headername')
@if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
    End Of Day
@endif 
@if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true)

Insurance Payments
@endif     
@if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' || Request::route('filters') == 'invoiced')
Tagged Cases
@endif
@endsection

@section('content')
<div class="card">

  {{-- model --}}
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tagged Cases</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('billing_company_invoice.store') }}" enctype="multipart/form-data">
              @csrf
              
            <div class="container">
              <span id="invoice_head_id"></span>
              <label for="" style="font-weight: bold">Patient Count:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="count_patient"></span>
              <span class="count_patient"></span><br>
             

              <label for="" style="font-weight: bold">Total Insurance Collection:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="total_insurance_collection">$</span>
              <span class="total_insurance"></span><br>
           
              <label for="" style="font-weight: bold">Billing Company Margin:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="billing_company_percentage">4.5%</span>
              <input type="text" name="billing_company_percentage" id="" value="4.5" hidden><br>
            
              <label for="" style="font-weight: bold">Total Billing Company Invoice :</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="total_tagged_cases">$</span>
              <span class="total_tagged_cases"></span><br>
           
              <label for=""style="font-weight: bold">Invoice PDF:</label>
              <input type="file" name="image" id="image"  required>
            
              <label for="" style="font-weight: bold">Invoice Name:</label>
              <input type="text" name="invoice_name" id="invoice_name"  placeholder="Enter Invoice Name" required class="form-control col-8">
            
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="form-submit">Submit</button>
        </div>
      </form>
      </div>
    </div>
  </div>
  {{-- end model --}}
    <div class="card-header">
      
      @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
         <a href="{{ route('patient_checkout_form.create',['id'=>'0']) }}" class="btn btn-primary" style="float: right">+ Add Checkout Patient</a>

       @endif 
      @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
       <h4 class="card-title">End of Day</h4>
      @endif
      @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true)
        <h4 class="card-title">Insurance Payments</h4>
      @endif
      @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' || Request::route('filters') == 'invoiced')
      Tagged Cases
      @endif
    </div>

    <div class="card-body">
      @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
     

        <div class="row">
          <div class="col-8">
            <form action="{{ url('end_of_day_filter') }}" method="get" id="filter_form" class="form-inline">
              @csrf
                  <div class="form-group mr-3" style="margin-left: 0.4cm">
                      @if ( request()->has('date') == true)
                    <input type="date" name="date" id="" placeholder="Enter Date" class="form-control" value="{{ app('request')->input('date') }}">
                      @elseif (request()->date )
                      <input type="date" name="date" id="" placeholder="Enter Date" class="form-control" value="{{ request()->date}}">
                      @endif
                  </div>
                  <div class="form-group mr-2">
                      <select name="clinic_id" id="" class="form-control">
                          
                          @foreach ($clinic_array as $clinic)
                          @if (request()->clinic_id == $clinic->id)
                          <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                          @elseif( request()->has('clinic_id') == true)
                          <option value="{{ $clinic->id }}" <?php if ($_GET['clinic_id'] == $clinic->id) { ?>selected="true" <?php }; ?>> {{ $clinic->location }} </option>
                          @elseif (session()->get('location_name') == $clinic->id )
                          <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                          @else
                          <option value="{{ $clinic->id }}">{{ $clinic->location }}</option>
                          @endif
                          @endforeach
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary btn-lg">Submit</button>
            </form>
          </div>
        
        <div class="col-2">
          <input type="button" id="btnExport" value=" Excel" class="btn btn-success btn-lg" />
       </div>
      </div>
    @endif 
    {{-- @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
        <button type="button" id="button_filter" class="btn btn-primary" style="margin-left: 0.4cm">Filter</button>
     @endif    --}}
     @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' || Request::route('filters') == 'invoiced')
     <div class="btn-group" role="group" aria-label="Basic example" style="margin-left: 0.4cm">
      <a href="../tagged_cases/all" class="btn btn-primary">All</a>
      <a href="../tagged_cases/completed" class="btn btn-primary">Completed</a>
      <a href="../tagged_cases/pending" class="btn btn-primary">Pending</a>
      <a href="../tagged_cases/invoiced" class="btn btn-primary">Invoiced</a>
      @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed')
     
          <button style="display: none" id="one" type="submit" class="btn btn-lg btn-primary "  data-toggle="modal" data-target="#exampleModal">Batch Together</button>
    
      @endif
    </div>

     @endif
 

        <div class="table-responsive">
          @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true)
          <div class="container-fluid row">
            <form action="{{ url('insurance_date') }}" method="get" class="form-inline">
              
                  <div class="form-group mr-2" style="margin-left: 0.4cm">
                    @if ( request()->has('from_date') == true)
                    From Date:<input type="date" name="from_date" id="" class="form-control" required  value="{{ app('request')->input('from_date') }}">
                      @else
                      From Date:<input type="date" name="from_date" id="" class="form-control" required>
                      @endif
                  </div>
                  <div class="form-group mr-2">
                    @if ( request()->has('to_date') == true)
                      To Date: <input type="date" name="to_date" id="" class="form-control" required value="{{ app('request')->input('to_date') }}">
                      @else
                      To Date: <input type="date" name="to_date" id="" class="form-control" required>
                      @endif
                  </div>
                    <button type="submit" class="btn btn-lg btn-primary" style="margin-top: -0.4cm">Submit</button>
            </form>
          </div>
          @endif
          <br>
          <div class="container-fluid">
           
              @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
              <div class="col-lg-9" id="dvData" style="float: left">
              @else
              <div class="col-12">
              @endif
              @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true || Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed')

                <table id="data_table" class="display" >
              @else
              <table class="table">
              @endif
             
                  <thead>
                    @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' )
                    <th></th>
              @endif    
               
              @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' || Request::route('filters') == 'invoiced' )
              <th>Date</th>
                  <th>Day</th>
                
                  <th>Location</th>
             
              @endif
              @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true || Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                    <th>Date</th>
              @endif
                  <th>Patient Name</th>
                  @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                    <th>Location</th>
                  @endif
                  <th>Type of Exam</th>
                  <th>Insurance</th>
                  <th>Copay/PP</th>
                  @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                  <th>Claimed</th>
                  @endif
                  <th>Insurance/PP</th>
                  @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                  <th>Balance</th>
                  @endif
                  <th>Copay Collection</th>
                  {{-- @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                  <th>Post Insurance
                     Balance</th>
                  @endif --}}
                  <th>Doctor</th>
                  @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true  )
                  {{-- <th>OOP</th> --}}
                  @endif
                  <th>
                  @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                  Transaction 
                    Status
                  @endif
                  @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' || Request::route('filters') == 'invoiced')
                  Status
                
                  @endif
                </th>
                
                  <th>Action</th>
                  
                </thead>
                <tbody>
              
                @foreach ($reports as $data)
                 
                   
                  <tr>
                    @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed')
                    @if ($data->is_completed == 1 && $data->billing_company_invoice_id == Null)
                    <td><input type="checkbox" name="invoice_head[]" value="{{ $data->id }}" class="check_box" id="check-box" ></td>
                    @else
                    <td></td>
                    @endif
                    @endif
                    @if(Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true)
                      <td>{{ date('m/d/y',strtotime($data->invoice_date)) }}</td>
                    @endif
                    @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed' || Request::route('filters') == 'invoiced')
                 
                      <td>{{ date('m/d/y',strtotime($data->invoice_date)) }}</td>
                      <td>{{ date('l',strtotime($data->invoice_date)) }}</td>
               
                      <td>{{ $data->location}}</td>
                  
                    @endif  
                    @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
                    <td>{{ date('m/d/y',strtotime($data->invoice_date)) }}</td>

                    @endif
                      <td>{{ $data->patient_firstname }}{{"  "}}{{$data->patient_lastname}}</td>
                      @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                          <td>{{ $data->location }}</td>
                      @endif
                      <td>{{ $data->title }}</td>
                      <td>@if ($data->p_insurance_title != '' ||$data->s_insurance_title != '')
                        {{ $data->p_insurance_title }}{{ " " }}{{ $data->s_insurance_title }}
                      @else
                      {{ "" }}
                      @endif</td>
                      <td class="copay">@if ($data->total_amount == '')
                          {{ '$0' }}
                      @else
                      {{ $data->total_amount }}
                      @endif</td>
                      @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                      <td>
                        @if ($data->claim_status == 'Unclaimed')
                        <span class="custom-badge status-red" onclick="claim_status_toggle({{ $data->id }})" id="label_claim_{{ $data->id }}" style="cursor: pointer">{{ $data->claim_status }}</span>
                        @elseif ($data->claim_status == 'Claimed')   
                        <span class="custom-badge status-green" onclick="claim_status_toggle({{ $data->id }})" id="label_claim_{{ $data->id }}" style="cursor: pointer">{{ $data->claim_status }}</span>
                     
                        @endif
                        <select name="claim_status" id="select_claim_{{ $data->id }}" onchange="claim_status(this.value,{{ $data->id }})" style="display: none" >
                          <option value="">Select</option>
                          <option value="Claimed">Claimed</option>
                          <option value="Unclaimed">Unclaimed</option>
                        </select>
                      </td>
                      @endif
                      <td>@if ($data->insurance_payment == '$0')
                          {{ "" }}
                      @else
                      {{ $data->insurance_payment }}
                      @endif</td>
                      @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true  || Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true)
                      <td>{{ $data->total_balance }}</td>
                      @endif
                      <td>{{ $data->payment_title }}</td>
                      {{-- @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                        <td></td>
                      @endif --}}
                      <td>{{ $data->name }}</td>
                      @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true )
                      {{-- <td>@if ($data->is_out_of_pocket == 1)
                        <span class="custom-badge status-green">{{ "OOP" }}</span>
                      @else
                        <span class="custom-badge status-red" id="approved">{{ "Not OOP " }}</span>
                      @endif</td> --}}
                      @endif
                      <td>
                      @if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true )
                        @if ($data->is_completed == 0 || $data->is_completed == NULL)
                          <span class="custom-badge status-red" id="approved">{{ "Incomplete " }}</span>
                      @else
                        <span class="custom-badge status-green" id="approved">{{ "Complete " }}</span>
                      @endif
                       
                      @endif
                     
                      @if (Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed')
                     
                        @if ($data->is_completed == 0 || $data->is_completed == NULL)
                        <span class="custom-badge status-red" id="approved">{{ "Pending" }}</span>
                        @else
                        <span class="custom-badge status-green" id="approved">{{ "Completed" }}</span>
                        @endif
                        @endif
                     
                      @if (Request::route('filters') == 'invoiced')
                      @if ($data->billing_company_invoice_id > 0)
                      <span class="custom-badge status-green" id="invoiced">{{ "Invoiced " }}</span>
                      @else
                      <span class="custom-badge status-red" id="invoiced">{{ "No Invoiced " }}</span>
                      @endif
                      @endif
                      </td>
                     
                      <td><a href="{{ route('patient_checkout_form.create',['id'=> $data->id]) }}" style="color: rgb(8, 155, 74)"><i class="fa fa-edit"></i></a> | <a  style="color: red;cursor: pointer;" id="{{ $data->id }}" data-delete="{{ $data->id }}" class="delete_btn"><i class="fa fa-trash"></i></a>
                    </td>
                    
                  </tr>
              
             
                  @endforeach
                
                </tbody>
                @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
                <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                     
                      <th><span class="total_copay"></span></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                </tfoot>
                @endif
              </table>
              </div>
          <div class="row">
            @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
              <div class="col-lg-12" >
                  @csrf
                  <table class="table">
                    <thead>
                      <tr>
                        <th style="background-color: rgb(250, 250, 84)">Fundus</th>
                        <th style="background-color: rgb(50, 230, 230)">Target</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        @if ($saling_percentage == null)
                        <td style="background-color:rgb(247, 120, 120)">
                        @else
                          @if ($saling_percentage[0]->fun_medical_ratio > $saling_percentage[0]->medical_target)
                          <td style="background-color:rgb(75, 230, 75)">
                          @else
                          <td style="background-color:rgb(247, 120, 120)">
                          @endif
                        @endif
                        @if ($saling_percentage == null)
                            {{ '' }}
                        @else
                            {{ $saling_percentage[0]->fun_medical_ratio }}
                        @endif</td>
                        <td style="background-color: rgb(50, 230, 230)">@if ($saling_percentage == null)
                            {{ '' }}                          
                        @else
                            {{$saling_percentage[0]->medical_target  }}
                        @endif</td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th style="background-color: rgb(250, 250, 84)">OASIS</th>
                        <th style="background-color: rgb(50, 230, 230)">Target</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                      @if ($saling_percentage == null)
                        <td style="background-color:rgb(247, 120, 120)">
                      @else
                          @if ($saling_percentage[0]->oasis_ratio > $saling_percentage[0]->oasis_target)
                          <td style="background-color:rgb(75, 230, 75)">
                          @else
                          <td style="background-color:rgb(247, 120, 120)">
                          @endif
                      @endif
                       
                     
                        @if ($saling_percentage == null)
                            {{ '' }}
                        @else
                            {{$saling_percentage[0]->oasis_ratio }}
                        @endif</td>
                        <td style="background-color: rgb(50, 230, 230)">@if ($saling_percentage == null)
                            {{ '' }}
                        @else
                        {{ $saling_percentage[0]->oasis_target }}
                        @endif</td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th style="background-color: rgb(250, 250, 84)">CL Ratio</th>
                        <th style="background-color: rgb(50, 230, 230)">Target</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                       @if ($saling_percentage == null)
                       <td style="background-color:rgb(247, 120, 120)">
                        @else
                        @if ($saling_percentage[0]->cl_ratio > $saling_percentage[0]->cl_target)
                        <td style="background-color:rgb(75, 230, 75)">
                        @else
                        <td style="background-color:rgb(247, 120, 120)">
                        @endif
                       @endif
                       
                        @if ($saling_percentage == null)
                            {{ '' }}
                        @else
                            {{ $saling_percentage[0]->cl_ratio }}
                        @endif</td>
                        <td style="background-color: rgb(50, 230, 230)">@if ($saling_percentage == null)
                            {{ '' }}
                        @else
                            {{ $saling_percentage[0]->cl_target }}
                        @endif</td>
                      </tr>
                    </tbody>
                    <thead>
                      <tr>
                        <th style="background-color: rgb(250, 250, 84)" colspan="2">Total Patients</th>
                        {{-- <th style="background-color: rgb(50, 230, 230)">Target</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                       
                        <td style="background-color:rgb(214, 209, 209)" colspan="2">
                     
                        @if ($saling_percentage == null)
                            {{ '' }}
                        @else
                            {{$saling_percentage[0]->total_patients }}
                        @endif</td>
                        {{-- <td style="background-color: rgb(50, 230, 230)">@if ($saling_percentage ==null)
                            {{ '' }}
                        @else
                            {{$saling_percentage[0]->patient_target }}
                        @endif</td> --}}

                      </tr>
                    </tbody>
                </table>
              </form>
              </div>
            @endif
           
         
        <br>
        @if (Request::route('filter') == 'end_of_the_day' || request()->has('clinic_id') == true || request()->has('date') == true)
      
            <div class="col-lg-12"  style="float: right">
              <label for="" style="font-weight: bold">Cash Till</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="" style="color: red; font-weight:bold">{{ ($end_day[0]->exist_value == 0) ? '' : 'Day has been Closed!'  }}</label>
              <form action="" method="post" id="cash_till_form">
                @csrf
              <table class="table">
                <tbody>
                  <tr style="background-color:rgb(214, 209, 209)">
                    <td >Opening Balance</td>
                    <input type="text" name="clinic_id" id="c_id"  value="{{ is_null($saling_percentage[0]->clinic_id) ? '' : $saling_percentage[0]->clinic_id }}" hidden >
                    
                    <input type="text" name="cash_till_date" id="cash_till_date" value="{{ ($reports == null) ? '' : $reports[0]->invoice_date }}" hidden>
                    <td><input type="text" name="yester_day_balance" id="yester_day_balance" class="form-control col-8"  value="{{ $yesterday_balance == null ? '' : $yesterday_balance[0]->yesterday_balance }}" style="color: red;font-weight:bold" placeholder="$"></td>
                  </tr>
                  <tr style="background-color:rgb(214, 209, 209)">
                    <td>Cash Received Today</td>
                    <td><input type="text" name="cash_received" id="cash_received"  class="form-control col-8"  value="{{ ($yesterday_balance == null) ? '' : $yesterday_balance[0]->cash_received_today }}" placeholder="$">
                      
                  </tr>
                  <tr style="background-color:rgb(214, 209, 209)">
                    <td>Any Refunds</td>
                    <td><input type="text" name="any_refunds" id="refunds"  class="form-control col-8" value="{{ $yesterday_balance == null ? '' : $yesterday_balance[0]->any_refunds }}" placeholder="$"></td>
                  </tr>
                  <tr style="background-color:rgb(214, 209, 209)">
                    <td>Extra Money Added</td>
                    <td><input type="text" name="extra_money_added" id="extra_money_added"  class="form-control col-8" value="{{ $yesterday_balance == null ? '' : $yesterday_balance[0]->extra_money_added	 }}" placeholder="$"></td>
                  </tr>
                  <tr style="background-color:rgb(214, 209, 209)">
                    <td>
                    
                      <select name="manager_id" id="manager_id" class="form-control">
                       
                      @foreach ($manager_user_array as $data)
                          <option value="{{ $data->id }}">{{ $data->name }}</option>
                      @endforeach
                    </select>
                  </td>
                    <td><input type="text" name="given_money" id="cash_given" class="form-control col-8" value="{{ $yesterday_balance == null ? '' : $yesterday_balance[0]->given_money	 }}" placeholder="$"></td>
                  </tr>
                  <tr style="background-color: rgb(250, 250, 84)">
                    <td>End Balance In Box</td>
                    <td style="color:red;font-weight:bold"><span id="end_balance">$</span>
                    <input type="text" name="end_balance" id="end_balance_id" value="" hidden></td>
                  </tr>
                </tbody>
            </table>
              <button type="submit" class="btn btn-primary btn-block" id="form_submit">Close Day</button>
              </form>
            </div>
          </div>
        @endif
        </div>
      </div>
    </div>
  </div>


@endsection


@section('scripts')
@if (Request::route('filter') == 'insurance_payments' || request()->has('from_date') == true && request()->has('to_date') == true || Request::route('filter') == 'tagged_cases' || Request::route('filters') == 'all' || Request::route('filters') == 'pending' || Request::route('filters') == 'completed')

<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>

<script>
  
  $(document).ready(function(){

    var table = $('#data_table').DataTable( {
        //dom: 'Bfrtip',
         dom: 'lBfrtip',
        // responsive: true,
        // pageLength: 10,
        // lengthChange: true,
        // ordering:false,
        
        // buttons: [
        //     'colvis',
        //     'excelHtml5',
        //     'csvHtml5',
            
        // ]
        "paging":   true,
        "lengthChange": false,
        "ordering": false,
        "info":     false,
        'searching':true,
        buttons: [
           
            'excelHtml5'
          
            
        ]
       
    } );
    table.buttons().container()
        .appendTo( '#example_wrapper .small-6.columns:eq(0)' );
       
  });
  
</script>
@endif
<script>
    $(document).ready(function() {


      var total_c = 0;
          $(".copay").each(function(){
            var val = $(this).text();
            var result = val.split("$");
           // console.log(result[1]);
            total_c += Number(result[1]);
          });
          //console.log(total_c);

          $(".total_copay").html("$"+total_c.toFixed(2));
       

    $("#yester_day_balance,#cash_received, #refunds, #extra_money_added,#cash_given").on("keyup",function(){
      var split_balance = $("#yester_day_balance").val();
     // var  = str.split("$");
      var cash_received = $("#cash_received").val();
     // var  = cash.split("$");
      var refunds = $("#refunds").val();
      var extra_money_added = $("#extra_money_added").val();
      var cash_given = $("#cash_given").val();

      var end_balance = Number(split_balance) + Number(cash_received) - Number(refunds) + Number(extra_money_added) - Number(cash_given);
      //console.log(end_balance);
      $("#end_balance").html("$"+end_balance.toFixed(2));
      $("#end_balance_id").val(end_balance.toFixed(2));

    }).keyup();
      // checkbox
      $('[type=checkbox]').click(function ()
            {
                var checkedChbx = $('[type=checkbox]:checked');
                var String
                if (checkedChbx.length > 0)
                {
                    $('#one').show();
                    $('#print').show();
                    $('#print_order').show();
                    console.log(checkedChbx);
                }
             else {
                $("#one").hide();
                $("#print").hide();
                $("#print_order").hide();
            }
            if (checkedChbx.length >= $('[type=checkbox]').length)
                {
                    $('#all').show();
                    
                    console.log(length);
                }
                else
                {
                    $('#all').hide();
                }
        
         
    })


    // count patient 
    $("input[name='invoice_head[]']").on("change", function(){
           //update count every check change event
           var count = $("input[name='invoice_head[]']:checked").length;
          // console.log(count);
           $("#count_patient").html(count);
           $(".count_patient").html("<input type='text' name='count_patient' id='count' value='"+count+"' hidden>");
          


          // var total_insurance =  $(".total_insurance").html();
        
   
   

    });
        $('#selectAll').click(function(e){
           var table= $(e.target).closest('table');
           $('td input:checkbox',table).attr('checked',e.target.checked);
        
           });
           $('[type="checkbox"]').click(function(){
        var val = [];
        $('[type="checkbox"]:checked').each(function(i){
          val[i] = $(this).val();
        });
       //console.log(val);
       $("#invoice_head_id").html("<input type='text' name='invoice_head_id[]' id='count' value='"+val+"' hidden>")
       var url = "{{ url('insurance_payment') }}/"+val;
            $.ajax({
                url: url,
                type: "GET",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(data) {
                var total_insurance = data;
                 $('#total_insurance_collection').html("$"+data);
                 $('.total_insurance').html("<input type='text' name='total_insurance_collection' id='count' value='"+data+"' hidden>");
                 var count_patient = $("#count_patient").html();
                 //console.log(data);
                var str = $("#billing_company_percentage").html();
                var billing_company_percentage = str.split("%");
                 // console.log(billing_company_percentage[0]);
                var percentage = (Number(total_insurance) * Number(billing_company_percentage[0])) / 100;
                //console.log(percentage);
                 $("#total_tagged_cases").html("$"+percentage);
                 $(".total_tagged_cases").html("<input type='text' name='invoice_amount' id='count' value='"+percentage+"' hidden>");
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
           });


          
   
   $("#button_filter").on("click",function(){
      $("#filter_form").toggle();
   });


   $("#form_submit").click(function(e){
   
   var data = $("#cash_till_form").serialize();
   var yester_day_balance = $("#yester_day_balance").val();
   console.log("y"+yester_day_balance);
   var cash_received = $("#cash_received").val();
   console.log("c"+cash_received);
   var any_refunds = $("#refunds").val();
   console.log("r"+any_refunds);
   var extra_money_added = $("#extra_money_added").val();
   console.log("e"+extra_money_added);
   var manager_id = $("#manager_id").val();
   console.log("m"+manager_id);
   var end_balance = $("#end_balance_id").val();
   console.log("eb"+end_balance);
   var given_money = $("#cash_given").val();
   var cash_till_date = $("#cash_till_date").val();
   console.log("CG"+given_money);
   var clinic_id = $("#c_id").val();
   
   var url = "{{ url('cash_till') }}";
            $.ajax({
                url: url,
                type: "POST",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                   yester_day_balance: yester_day_balance, 
                   cash_received: cash_received, 
                   extra_money_added: extra_money_added, 
                   any_refunds: any_refunds, 
                   manager_id: manager_id, 
                   end_balance: end_balance, 
                   given_money:given_money,
                   cash_till_date:cash_till_date,
                   clinic_id:clinic_id,
                },
                success: function(data) {
                  console.log(data);
                  if(data == 1){
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Day has Been Closed!',
                      showConfirmButton: false,
                      timer: 2500
                    })
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
            e.preventDefault();
   });
  //  for delete purpose
  $(".delete_btn").click(function(){
          var delete_id = $(this).attr("id");
          var th=$(this);
          console.log(delete_id);
          var url = "{{url('patient_checkout_delete')}}/"+delete_id;
          Swal.fire({
							  title: 'Are you sure?',
							  text: "You won't be able to revert this!",
							  type: 'warning',
							  showCancelButton: true,
							  confirmButtonColor: '#3085d6',
							  cancelButtonColor: '#d33',
							  confirmButtonText: 'Yes, delete it!'
							}).then(function(result){
                if (result.isConfirmed)  
                  {
                      $.ajax({
                      
                        url : url,
                        type : 'PUT',
                        cache: false,
                        data: {_token:'{{ csrf_token() }}'},
                        success:function(data){
                         if (data == 1) {
                          Swal.fire({
                                title:'Deleted!',
                                text:'Your file and data has been deleted.',
                                type: 'success',
                              })
                              th.parents('tr').hide();
                              location.reload();

                            }
                          else{
                                Swal.fire({
                                    title: 'Oopps!',
                                    text: "something went wrong!",
                                    type: 'warning',
                          			})
                          		}
                         }
                        
                        });
                }
              });
               
        });
       
} );
$("#btnExport").click(function (e) {
          window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#dvData').html()));

    e.preventDefault();
});
function claim_status_toggle(id){
          $("#select_claim_"+id).show();
          $("#label_claim_"+id).hide();
        }
        function claim_status(claim_status,id){
          console.log(claim_status);
                    $("#label_claim_"+id).text($("#select_claim_"+id).find(":selected").text());
                    $("#select_claim_"+id).hide();
                    $("#label_claim_"+id).show();
                    var url = "{{ url('claim_status_update') }}/" + id;
                    
                    $.ajax({
                        url: url,
                        type: "PUT",
                        cache: false,
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        data: {
                            _token: '{{ csrf_token() }}',
                            claim_status: claim_status
                        },
                        success: function(response) {
                            console.log("success");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("update request failure");
                            //errorFunction(); 
                        }
                    });
        }
    </script>
@endsection