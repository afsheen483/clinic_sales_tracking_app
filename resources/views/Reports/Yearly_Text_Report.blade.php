

  @extends('layouts.master')
  
  
  @section('title')
    Yearly Tax Report 
  @endsection
  
  @section('headername')
  Yearly Tax Report 
    @endsection
  
  @section('content')
  
  
    
        <div class="card">
          <div class="card-header">
            <a class="btn btn-primary" href = "../accounts_receiveable_summary" style="float: right">Back</a>

            <h3 class="card-title">
                Yearly Tax Report 
            </h3>
             
        
          </div>
          
          <div class="card-body">
            <div class="row">
                <div class="col-11">
                  <form action="{{ url('date_filter') }}" method="get" class="form-inline">
                    @csrf
                   
                            <div class="form-group mr-2" style="margin-left: 0.4cm">
                              @if ( request()->has('from_date') == true)
                              From Date:<input type="date" name="from_date" id="" class="form-control" required  value="{{ app('request')->input('from_date') }}">
                                @else
                                From Date:<input type="date" name="from_date" id="" class="form-control" required value="{{ $first_date[0]->start }}">
                                @endif
                              </div>
                              
                              <div class="form-group mr-2">
                                @if ( request()->has('to_date') == true)
                                  To Date: <input type="date" name="to_date" id="" class="form-control" required value="{{ app('request')->input('to_date') }}">
                                  @else
                                  To Date: <input type="date" name="to_date" id="" class="form-control" required value="{{ $last_date[0]->end }}">
                                  @endif
                                  </div>
                               
                                    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                </form>
                </div>
                <div class="col-1">
                  <input type="button" id="btnExport" value=" Excel" class="btn btn-success btn-lg"/>
               </div>
            </div>
            <br>
            <div class="table-responsive">
              <div class="container-fluid" style="margin-top: 0.1cm" id="dvData">
               {{-- @php
                  dd($last_date[0]->end);
               @endphp --}}
              
                  <table id="" class="table">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Patient Name</th>
                        <th>Location</th>
                        <th>Type of Exam</th>
                        <th>Insurance</th>
                        <th>Copay/PP</th>
                        <th>Insurance/PP</th>
                        <th>Next Year</th>
                        <th>Copay Collection</th>
                        <th>Doctor</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($reports as $data)
                      <tr>
                        <td>{{ date('m/d/y',strtotime($data->invoice_date)) }}</td>
                        <td>{{ $data->patient_firstname }}{{"  "}}{{$data->patient_lastname}}</td>
                        <td>{{ $data->location }}</td>
                        <td>{{ $data->title }}</td>
                        <td>@if ($data->p_insurance_title != '' ||$data->s_insurance_title != '')
                          {{ $data->p_insurance_title }}{{ ", " }}{{ $data->s_insurance_title }}
                        @else
                        {{ "" }}
                        @endif</td>
                        <td class="copay">{{ $data->total_amount }}</td>
                        <td>{{ $data->insurance_payment }}</td>
                        <td>{{ $data->next_year_payment }}</td>
                        <td>{{ $data->payment_title }}</td>
                        <td>{{ $data->name }}</td>
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
  
  <script>
    $("#btnExport").click(function (e) {
          window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#dvData').html()));

    e.preventDefault();
});

  </script>
  @endsection