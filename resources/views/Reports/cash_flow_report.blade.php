

  @extends('layouts.master')
  
  
  @section('title')
    Cash Flow Report
  @endsection
  
  @section('headername')
  Cash Flow Report
    @endsection
  
  @section('content')
  
  
    
        <div class="card">
          <div class="card-header">
            <a class="btn btn-primary" href = "../accounts_receiveable_summary" style="float: right">Back</a>

            <h3 class="card-title">
                Cash Flow Report 
            </h3>
             
        
          </div>
          
          <div class="card-body">
            <div>
              <input type="button" id="btnExport" value=" Excel" class="btn btn-success btn-lg" style="margin-left:0.5cm"/>
           </div>
            <div class="table-responsive">
              <div class="container-fluid" style="margin-top:0.4cm" id="dvData">
               
                 
                  <table id="" class="table">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Opening Balance</th>
                        <th>Today Balance</th>
                        <th>Any Refunds</th>
                        <th>Extra Money Added</th>
                        <th>Given Money To Manager</th>
                        <th>Manager</th>
                       
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($reports as $data)
                      <tr>
                        <td>{{  date('m/d/y',strtotime($data->cash_till_date)) }}</td>
                        <td>{{ $data->location }}</td>
                        <td>${{ $data->opening_balance }}</td>
                        <td>${{ $data->cash_received_today }}</td>
                        <td>${{ $data->any_refunds }}</td>
                        <td>${{ $data->extra_money_added }}</td>
                        <td>${{ $data->given_money }}</td>
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