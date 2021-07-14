

  @extends('layouts.master')
  
  
  @section('title')
      Weekly Report
  @endsection
  
  @section('headername')
      Reporting Manager - Module
  @endsection
  
  @section('content')
  
  
    
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              Reporting Module - Manager
            </h3>
              <br>
             
            <h4>
              Weekly Report
          </h4>
          </div>
          
          <div class="card-body">
            @foreach ($reports as $data)
            <div class="table-responsive">
              <div class="container-fluid col-5">
               
                 
                  <table id="" class="table">
                  <tbody>
                      <tr >
                          <td style="font-weight: bold">Week{{ " " }}{{ $data->week_number }}</td>
                          <td style="font-weight: bold">Actual</td>
                          <td style="font-weight: bold">Targets</td>
                      </tr>
                      <tr>
                          <td>Doctors Day This Week</td>
                          <td>{{ $data->doctor_days }}</td>
                          <td></td>
                      </tr>
                      <tr>
                          <td>Labor Hours Total</td>
                          <td>{{ $data->labor_hours }}</td>
                          <td>{{ $data->labor_target }}</td>
                      </tr>
                      <tr>
                          <td>Labor Hours (Per Day)</td>
                          <td>{{ $data->labor_hours_per_day }}</td>
                          <td>{{ $data->labor_per_day_target }}</td>
                      </tr>
                      <tr>
                          <td>Fundus Ratio</td>
                          <td>{{ $data->fundus_ratio }}</td>
                          <td>{{ $data->fundus_target }}</td>
                      </tr>
                      <tr>
                        <td>Oasis Ratio</td>
                        <td>{{ $data->oasis_ratio }}</td>
                        <td>{{ $data->oasis_target }}</td>
                    </tr>
                    <tr>
                        <td># Patients (PER DAY)</td>
                        <td>{{ $data->total_patients }}</td>
                        <td>{{ $data->patient_target }}</td>
                    </tr>
                    <tr>
                        <td>Family/Recall Upsell</td>
                        <td>{{ $data->family_upsell_count }}</td>
                        <td>{{ $data->upsell_target }}</td>
                    </tr>
                  </tbody>
                  </tbody>
                </table>
             
              </div>
            </div>
           
            <br>
            @endforeach
          </div>
        </div>
  
      
  
  
  
  @endsection
  
  @section('scripts')
  
  
  @endsection