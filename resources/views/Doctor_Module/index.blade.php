
<style>
  td { font-size: 13px; }

</style>
@extends('layouts.master')


@section('title')
    Reporting Doctor - Module
@endsection

@section('headername')
Reporting Doctor - Module
@endsection

@section('content')


  
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Reporting Doctor - Module
        </h4>
        </div>
        
        <div class="card-body">
           <div class="row">
              <div class="col-11">
                <form metdod="get" action="{{ url('doctor_dropdown') }}" class="form-inline">
                  @csrf
                  
                          <div class="form-group mr-2" style="margin-left: 0.4cm">
                            <label for="">Doctor</label>&nbsp;
                            <select class="form-control" name="doctor_id" id="doctor" >
                                  <option value="all">All Doctor</option>
                                  @foreach ($doctor_user_array as $doctor)
                                  @if ( request()->has('doctor_id') == true)
                                  <option value="{{ $doctor->id }}" <?php if ($_GET['doctor_id'] == $doctor->id) { ?>selected="true" <?php }; ?>>{{ $doctor->name }}</option>
                                  @else
                                  <option value="{{ $doctor->id }}" >{{ $doctor->name }}</option>
  
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
              
                <table id="summary_table" class="table" style="widtd:100%">
                
                    <tr>
                      <td colspan="2" id="doctor_name"></td>
                     
                      <td  style="color: red">Target = 125;25 per day</td>
                      
                      
                    
                      <td  style="color: red">Target = 70%</td>
                      <td  style="color: red">Target = 50%</td>
                      <td  style="color: red">Target = 15%</td>
                      <td  style="color: red">Target = 10%</td>
                      <td rowspan="2" style="color: red; text-align:center">Weekly Revenue <br> 
                      Generated COPAY()</td>
                      <td rowspan="2" style="color: red;text-align:center">Weekly Revenue <br>
                      Generated (Total)</td>
                  </tr>
                    <tr>
                      <td style="color: red">Year</td>
                      <td style="color: red">Week</td>
                      <td style="color: red">Total Patients</td>
                      <td style="color: red">Fundus Ratio</td>
                      <td style="color: red">CL Ratio</td>
                      <td style="color: red">Medical Ratio</td>
                      <td style="color: red">OASIS Ratio</td>
                    </tr>
                    
                    
               
                <tbody>
                @foreach ($doctor_module_data as $data)
                    
                  <tr>
                    <td>{{ $data->year }}</td>
                    <td>{{ $data->week_number }}</td>
                    <td>{{ $data->total_patients }}</td>
                    <td>{{ $data->fundus_ratio }}</td>
                    <td>{{ $data->cl_ratio }}</td>
                    <td>{{ $data->medical_ratio }}</td>
                    <td>{{ $data->oasis_ratio }}</td> 
                    <td style="text-align:center" class="copayment">{{ $data->copayment }}</td> 
                    <td style="text-align:center" class="final_amount">{{ $data->final_amount }}</td> 
                  </tr>
                  @endforeach
  
                </tbody>
                <tfoot>
                   <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th rowspan="2" style="text-align:center" class="total_copay">$</th>
                    <th rowspan="2" style="text-align:center" class="final">$</th>
                   </tr>
                </tfoot>
              </table>

             
            </div>
          </div>
        </div>
      </div>

    



@endsection

@section('scripts')

<script>
    
        // function change_doctor(id){
        //     var url = "{{ url('doctor') }}/"+id;
        //     $.ajax({
        //         url: url,
        //         type: "GET",
        //         cache: false,
        //         data: {
        //             _token: '{{ csrf_token() }}',
        //         },
        //         success: function(data) {
        //             console.log(data);
                   
                  
        //         },
        //         error: function(jqXHR, textStatus, errortdrown) {
        //             console.log("request failure");
        //             //errorFunction(); 
        //         }
        //     });
        // }
        $(document).ready(function(){
          var total_c = 0;
          $(".copayment").each(function(){
            var val = $(this).text();
            var result = val.split("$");
            console.log(result[1]);
            total_c += Number(result[1]);
          });
          console.log(total_c);
          $(".total_copay").html("$"+total_c);

          var total = 0;
          $(".final_amount").each(function(){
            var val = $(this).text();
            var result = val.split("$");
            console.log(result[1]);
            total += Number(result[1]);
          });
          console.log(total);
          $(".final").html("$"+total);
        });

        $("#doctor").change(function(){
          //var name = $( "#doctor option:selected" ).text();
          console.log(name);
          $('#doctor_name').text($(this).find(":selected") ? $(this).find(":selected").text() : $(this).find(":first").text());
}).change();
          // localStorage.setItem(name);
//set the inner html to what is in local storage
      // return localStorage.getItem(name);
         // $("#doctor_name").html(name);
         $("#btnExport").click(function (e) {
          window.open('data:application/vnd.ms-excel,' +  encodeURIComponent($('#dvData').html()));

    e.preventDefault();
});
        
    </script>
  
@endsection