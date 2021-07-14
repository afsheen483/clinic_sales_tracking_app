
@extends('layouts.master')


@section('title')
Accounts Receiveable Summary 
@endsection

@section('headername')

Accounts Receiveable Summary
@endsection

@section('content')


  
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Accounts Receiveable Summary</h4>
        </div>
        
        <div class="card-body">
          <form action="{{ url('year') }}" method="GET" class="form-inline">
            @csrf
            
             
               <div class="form-group mr-2" style="margin-left: 0.4cm">
                <select name="year" id="" class="form-control" required>
                    <option value="">Select Year</option>
                       @foreach ($location_array as $data)
                         @if ( request()->has('year') == true)
                           <option value="{{ $data->year }}" <?php if ($_GET['year'] == $data->year) { ?>selected="true" <?php }; ?>>{{ $data->year }}</option>
                         @else
                           <option value="{{ $data->year }}">{{ $data->year }}</option> 
                          @endif                     
                    @endforeach                
                </select>
               </div>
              
                <button type="submit" class="btn btn-primary btn-lg" >Submit</button>
              
          </form>
          <br>
          <span id="msg_show"></span>
          <div class="table-responsive">
            <div class="container-fluid">
              <table  class="table" >
                <thead>
                <th>Week</th>
                <th>Year</th>
                <th>Total Patients</th>
                <th>Family Upsell</th>
                <th>Unpaid</th>
                <th>Unpaid %</th>
                <th>Comments</th>
                <th>Tag/Untag</th>
                <th></th>
              </thead>
              <tbody>
               
              @foreach ($reports as $data)
                
                <tr>
                  <td data-week="{{ $data->id }}" id="week_{{ $data->id }}">{{ $data->week_number }}</td>
                  <td data-year="{{ $data->id }}" id="year_{{ $data->id }}">{{ $data->year }}</td>
                  <td><a href="../all_patients/{{ $data->week_number }}{{ "_" }}{{ $data->year }}" target="_blank" >{{ $data->total_patients }}</a></td>
                  <td>{{ $data->family_upsell_count }}</td>
                  <td>{{ $data->unpaid_count }}</td>
                  <td>{{ $data->unpaid_percentage }}</td>
                  @if ($data->remarks == '')
                  <td contenteditable="true" data-remarks = "{{ $data->id }}" id="remarks_{{ $data->id }}" class="edit_remarks" style="cursor: pointer"> </td>
                  @else
                  <td contenteditable="true" data-remarks = "{{ $data->id }}" id="remarks_{{ $data->id }}" class="edit_remarks" style="cursor: pointer">
                      {{ $data->remarks }}
                  </td>
                  @endif
                  
                  <td>
                    @if ($data->is_paid == 1 )
                    <button type="submit" class="btn btn-danger submit" data-submit = "{{ $data->id }}" id="submit_{{ $data->id }}" disabled style="height: 26px;padding-top:0.6px"><i class="fa fa-remove show-icon" ></i></button>

                    @else
                    <button type="submit" class="btn btn-success submit" data-submit = "{{ $data->id }}" id="submit_{{ $data->id }}" style="height: 26px;margin-top:-0.6px"><i class="fa fa-check show-icon" ></i></button>

                    @endif
                  </td>
                 <td>
                   <a href="../weekly_report/{{ $data->week_number }}{{ "_" }}{{ $data->year }}"  class="btn btn-sm btn-success" style="color: white" target="_blank">Weekly Report</a>
                 </td>
                </tr>
                @endforeach

              </tbody>
            </table>
            </div>
          </div>
          <br><br>
         
          <div class="container col-5">
            <label for="" style="font-weight: bold">Summary</label>
              <table class="table">
                <thead>
                  <tr>
                    <th>Time Period</th>
                    <th>Unpaid Amount</th>
                    <th>% Unpaid</th>
                    <th>Targets Unpaid Should be</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($accounts_summary as $accounts)
                  <tr>
                    <td>{{ $accounts->time_period }}</td>
                    <td>{{ $accounts->unpaid_amount }}</td>
                    <td>{{ $accounts->unpaid_percentage }}</td>
                    <td>{{ $accounts->unpaid_target}}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>
          
          </div>
        </div>
      </div>

    



@endsection

@section('scripts')

<script>
    $(document).ready(function() {
    //     var table = $('#summary_table').DataTable( {
    //     //dom: 'Bfrtip',
    //     dom: 'lBfrtip',
    //     responsive: true,
    //     pageLength: 10,
    //     lengthChange: true,
    //     ordering:false,
        
    //     buttons: [
    //         'colvis',
    //         'excelHtml5',
    //         'csvHtml5',
            
    //     ]
    // } );
    // table.buttons().container()
    //     .appendTo( '#example_wrapper .small-6.columns:eq(0)' );
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
                   // console.log(checkedChbx);
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
    $('#selectAll').click(function(e){
           var table= $(e.target).closest('table');
           $('td input:checkbox',table).attr('checked',e.target.checked);
        
           });
           $('[type="checkbox"]').click(function(){
        var val = [];
        $('[type="checkbox"]:checked').each(function(i){
          val[i] = $(this).val();
        });
     
           });




           $(".submit").on("click",function(){
              var id = $(this).attr("data-submit");
             

              console.log(id);
              var week = $("#week_"+id).text();
              var year = $("#year_"+id).text();
              console.log(week +"  "+year);
              var url = "{{ url('tagged_invoices') }}";
            $.ajax({
                url: url,
                type: "POST",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    id:id,
                    week:week,
                    year:year,
                },
                success: function(data) {
                  console.log("update request success");
                  if (data == 1) {
                   
                      $("#msg_show").html("<div class='alert alert-success' role='alert' id='msg_show'>Invoice has been tagged successfully!</div>");
                  }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });

           });



           $(".edit_remarks").on("focusout",function(){
             // var id = $(this).attr("data-submit");
             var id = $(this).attr("data-remarks");

              console.log(id);
              var remarks = $("#remarks_"+id).text();
              var week = $("#week_"+id).text();
              var year = $("#year_"+id).text();
              console.log(week +"  "+year);
              var url = "{{ url('remarks_insert') }}/"+id;
            $.ajax({
                url: url,
                type: "PUT",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    remarks:remarks,
                    week:week,
                    year:year,
                },
                success: function(data) {
                 
                    if (data == 1) {
                      $("#msg_show").html("<div class='alert alert-success' role='alert' id='msg_show'>Your comment has been added, successfully!</div>");
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });

           });
} );
    </script>
@endsection