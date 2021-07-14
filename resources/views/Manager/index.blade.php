
<style>
  th { font-size: 13px;
     }
td { font-size: 13px; }
</style>
@extends('layouts.master')


@section('title')
    Reporting Manager - Module
@endsection

@section('headername')
    Reporting Manager - Module
@endsection

@section('content')


  
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Reporting Manager - Module
        </h4>
        </div>
        
        <div class="card-body">
          <div class="row">
            <div class="col-11">
              <form method="get" action="{{ url('location_dropdown') }}" class="form-inline">
                @csrf
                     <div class="form-group mr-2" style="margin-left: 0.4cm">
                       <label for="">Location:</label>&nbsp;
                          <select class="form-control" name="clinic_id" id="" >
                               
                                   @foreach ($clinic_array as $clinic)
                                     @if (session()->get('location_name') == $clinic->id )
                                        <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                                     @elseif( request()->has('clinic_id') == true)
                                        <option value="{{ $clinic->id }}" <?php if ($_GET['clinic_id'] == $clinic->id) { ?>selected="true" <?php }; ?>> {{ $clinic->location }} </option>
                                     @else
                                   <option value="{{ $clinic->id }}">{{ $clinic->location }}</option>
                                     @endif
                                @endforeach
                          </select>
                        </div>
                  
                       <button type="submit" class="btn btn-lg btn-primary" >Submit</button>
               </form>
            </div>
           <div class="col-1">
              <input type="button" id="btnExport" value=" Excel" class="btn btn-success btn-lg"/>
           </div>
        </div>
          <div class="table-responsive">
            <div class="container-fluid" id="dvData" style="margin-top: 0.6cm">
                @csrf
                <table id="" class="table" style="width:100%" >
                
                    <tr>
                      <th></td>
                      <td></td>
                      <td  style="color: red">Target = 16-20 
                      per day</td>
                        <td></td>
                      <td  style="color: red">Target = 2-5 per day</td>
                      <td  style="color: red">Target = 22 per day</td>
                      <td  style="color: red">Target = 75%</td>
                      <td  style="color: red">Target = 40%</td>
                      <td  style="color: red">Target = 15%</td>
                      <td  style="color: red">Target = 10%</th>
                  </tr>
                    <tr>
                      <td style="color: red">Year</td>
                      <td style="color: red">Week</td>
                      <td style="color: red">Labor Hours 
                        (Not Including Marcy)</td>
                      <td style="color: red">Doctor 
                        Days</td>
                      <td style="color: red">Family/Couple Upsell #</td>
                      <td style="color: red">Total Patients</td>
                      <td style="color: red">Fundus Ratio</td>
                      <td style="color: red">CL Ratio</td>
                      <td style="color: red">Medical Ratio</td>
                      <td style="color: red">OASIS Ratio</td>
                    </tr>
                   
               
                <tbody>
                  <tr>
                    <tr>
                      <td  style="color: red;text-align:center" colspan="2">DAILY MIN</td>
                      
                      <td style="color: red; text-align:center" colspan="2" >20</td>
                      
                      <td  style="color: red;text-align:center">2</td>
                      <td style="color: red;text-align:center">17</td>
                      <td style="color: red;text-align:center">60%</td>
                      <td style="color: red;text-align:center">30%</td>
                      <td style="color: red;text-align:center">8%</td>
                      <td style="color: red;text-align:center">10%</td>
                    </tr>
                    <tr>
                      <td  style="color:red ;text-align:center" colspan="2">DAILY MAX</td>
                     
                      <td  style="color: red; text-align:center" colspan="2">16</td>
                      
                      <td style="color: red;text-align:center">5</td>
                      <td style="color: red;text-align:center">14</td>
                      <td style="color: red;text-align:center">85%</td>
                      <td style="color: red;text-align:center">55%</td>
                      <td style="color: red;text-align:center">15%</td>
                      <td style="color: red;text-align:center">15%</td>
                    </tr>
                    <tr>
                      
                      
                      <td  style="color: red;text-align:center" colspan="2">Marcy Distribution</td>
                     
                      <td style="color: red; text-align:center" colspan="2">10%</td>
                    
                     
                      <td style="color: red;text-align:center">20%</td>
                      <td style="color: red;text-align:center">30%</td>
                      <td style="color: red;text-align:center">20%</td>
                      <td style="color: red;text-align:center">15%</td>
                      <td style="color: red;text-align:center">0%</td>
                      <td style="color: red;text-align:center">0%</td>
                    </tr>
                  </tr>
                @foreach ($manager_module_data as $data)
                 
                  <tr>
                    <td style="text-align:center"><input type="text" name="year[]" hidden value="{{ $data->year }}" class="year">{{ $data->year }}</td>
                    <td style="text-align:center"><input type="text" name="week[]" hidden value="{{ $data->week_number }}" class="week">{{ $data->week_number }}</td>
                    @if ($data->labor_hours == '')
                    <td style="text-align:center;" ><span class="hours-editable" data-hours = "hours_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" id="labor_hours_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" style="cursor:pointer;text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                    @else
                    <td style="text-align:center;"><span class="hours-editable" data-hours = "hours_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" id="labor_hours_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" style="cursor:pointer;text-align:center">{{ $data->labor_hours }}</span></td>
                    @endif
                    @if ($data->doctor_days == '')
                    <td style="text-align:center;" ><span class="days-editable" data-days = "days_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" id="doctor_days_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" style="cursor:pointer;text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>

                    @else
                    <td style="text-align:center;"><span class="days-editable" data-days = "days_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" id="doctor_days_{{ $data->week_number }}{{ "_" }}{{ $data->year }}" style="cursor:pointer;text-align:center">{{ $data->doctor_days }}</span></td>

                    @endif
                    <td style="text-align:center">{{ $data->family_upsell_count }}</td>
                    <td style="text-align:center">{{ $data->total_patients }}</td>
                    <td style="text-align:center">{{ $data->fundus_ratio }}</td>
                    <td style="text-align:center">{{ $data->cl_ratio }}</td>
                    <td style="text-align:center">{{ $data->medical_ratio }}</td>
                    <td style="text-align:center">{{ $data->oasis_ratio }}</td> 
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
    $(document).ready(function() {
   

    $('.hours-editable,.days-editable').on('click', function() {
      $('.hours-editable,.days-editable').each(function() {
        $(this).attr('contentEditable',true);
            });
    });

    $('.hours-editable').on('focusout', function() {
     // var val = $(this).val();
        var year_week = $(this).attr("data-hours");
       // console.log(id);
        var val = $("#labor_"+year_week).text();
        var labor_hours_value = $.trim(val);
       // console.log(val);

       var url = "{{ url('labor_hours_insert') }}";
            $.ajax({
                url: url,
                type: "POST",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    year_week: year_week,
                    labor_hours_value:labor_hours_value,

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
    


    //doctor days insert

     

    $('.days-editable').on('focusout', function() {
     // var val = $(this).val();
        var year_week = $(this).attr("data-days");
       console.log(year_week);
        var val = $("#doctor_"+year_week).text();
        var doctor_days_value = $.trim(val);
        console.log(val);

       var url = "{{ url('doctor_days_insert') }}";
            $.ajax({
                url: url,
                type: "POST",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    year_week: year_week,
                    doctor_days_value:doctor_days_value,

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
$("#btnExport").click(function (e) {
          window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#dvData').html()));

    e.preventDefault();
});

    
    </script>
@endsection