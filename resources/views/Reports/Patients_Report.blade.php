

  @extends('layouts.master')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
  <style>
     td{
      font-size: 15px !important;
    }
  </style>
  @section('title')
  All Patient's Report
  @endsection
  
  @section('headername')
  All Patient's Report
    @endsection
  
  @section('content')
  
  
    
        <div class="card">
          <div class="card-header">
            <a class="btn btn-primary" href = "../accounts_receiveable_summary" style="float: right">Back</a>

            <h3 class="card-title">
              All Patient's Report
            </h3>
             
            
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-11">
                <form action="{{ url('date_filter_all_patient') }}" method="get" class="form-inline">
                  @csrf
                          <div class="form-group mr-2" style="margin-left: 0.4cm">
            
                            @if ( request()->has('from_date') == true)
                            From Date:<input type="date" name="from_date"  class="form-control" required  value="{{ app('request')->input('from_date') }}">
                              @else
                              From Date:<input type="date" name="from_date"  class="form-control" required value="<?php echo date("Y-m-d") ?>">
                              @endif
                          </div>
                          <div class="form-group mr-2">
                            @if ( request()->has('to_date') == true)
                              To Date: <input type="date" name="to_date" class="form-control" required value="{{ app('request')->input('to_date') }}">
                              @else
                              To Date: <input type="date" name="to_date"  class="form-control" required value="<?php echo date("Y-m-d") ?>">
                              @endif
                              </div>
                            
                                  <button type="submit" class="btn btn-lg btn-primary" >Submit</button>
          
              </form>
              </div>
            </div>
<br>
           <div class="row">
            <div class="col-11">
              <div class="btn-group" role="group" aria-label="Basic example" style="margin-left: 0.4cm">
                <a href="../transaction_report/all" class="btn btn-primary">All</a>
                <a href="../transaction_report/completed" class="btn btn-primary">Completed</a>
                <a href="../transaction_report/incompleted" class="btn btn-primary">Incompleted</a>
                
              </div>
             </div>
             {{-- <div class="col-1">
              <input type="button" id="btnExport" value=" Excel" class="btn btn-success"/>
             </div> --}}
           </div>
            <div class="table-responsive">
              <div class="container-fluid" style="margin-top:0.4cm;" id="dvData">
               
                 
                  <table id="example" class="table">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Week</th>
                        <th>Year</th>
                        <th>Location</th>
                        <th>Patient Name</th>
                        <th>Type of Exam</th>
                        <th>Insurance</th>
                        <th>Copay/PP</th>
                        <th>Insurance/PP</th>
                        <th>Copay Collection</th>
                        <th>Doctor</th>
                        <th> Transaction 
                          Status</th>
                        <th>Action</th>
                       
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($reports as $data)
                      <tr>
                        <td>{{ date('m/d/y',strtotime($data->invoice_date)) }}</td>
                        <td>{{ $data->week_number }}</td>
                        <td>{{ $data->year }}</td>
                        <td>{{ $data->location }}</td>
                        <td>{{ $data->patient_firstname }}{{"  "}}{{$data->patient_lastname}}</td>
                        <td>{{ $data->title }}</td>
                        <td>@if ($data->p_insurance_title != '' ||$data->s_insurance_title != '')
                          {{ $data->p_insurance_title }}{{ ", " }}{{ $data->s_insurance_title }}
                        @else
                        {{ "" }}
                        @endif</td>
                        <td class="copay">{{ $data->total_amount }}</td>
                        <td>{{ $data->insurance_payment }}</td>
                        <td>{{ $data->payment_title }}</td>
                        <td>{{ $data->name }}</td>
                        <td>
                          @if ($data->is_completed == 0 || $data->is_completed == NULL)
                          <span class="custom-badge status-red" id="approved">{{ "Incomplete " }}</span>
                      @else
                        <span class="custom-badge status-green" id="approved">{{ "Complete " }}</span>
                      @endif
                        </td>
                        <td><a href="{{ route('patient_checkout_form.create',['id'=> $data->id]) }}" style="color: rgb(8, 155, 74)"><i class="fa fa-edit"></i></a> 
                      </tr>
                      @endforeach
                    </tbody>
                </table>
             
              </div>
            </div>
          </div>
        </div>
  
      
  
  
  
  @endsection
  
  @section('scripts')
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
        <script>
//           $("#btnExport").click(function (e) {
//           window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#dvData').html()));

//     e.preventDefault();
// });
$(document).ready(function(){
  var table = $('#example').DataTable( {
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
  @endsection