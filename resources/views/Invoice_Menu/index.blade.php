
<style>
  th { font-size: 14px; }
td { font-size: 12px; }
</style>
@extends('layouts.master')


@section('title')
    Billing Company Invoice
@endsection

@section('headername')
Billing Company Invoice
@endsection

@section('content')


  
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Billing Company Invoice
        </h4>
        </div>
       
        <div class="card-body">
          <div id="error">

          </div>
          <div class="btn-group" role="group" aria-label="Basic example" style="margin-left: 0.4cm">
            <a href="../invoice_menu/all" class="btn btn-primary">All</a>
            <a href="../invoice_menu/approved" class="btn btn-primary">Approved</a>
            <a href="../invoice_menu/pending" class="btn btn-primary">Pending</a>
          </div>
          <div class="table-responsive">
            <div class="container-fluid">
             
                <table id="summary_table" class="table" style="width:100%">
                  <thead>
                   <tr>
                        <th>Invoice Name</th>
                        <th>Invoice Date</th>
                        <th>Total Patients</th>
                        <th>Total Insurance Collection</th>
                        <th>Billing Company Margin</th>
                        <th>Total Billing Company Invoice</th>
                        <th>Invoice PDF</th>
                        <th>Status</th>
                        <th>   @hasanyrole('admin|Accountant')Approve/@endhasanyrole View</th>
                      
                   </tr>
                </thead>
                <tbody>
                @foreach ($invoice_data as $data)
                    
                  <tr>
                    <td>{{ $data->invoice_title }}</td>
                    <td>{{ date('m/d/y',strtotime($data->billing_invoice_date)) }}</td>
                    <td style="text-align: center"><a href="../invoice_details/{{ $data->invoice_head_id }}" target="_blank">{{ $data->count_of_patients }}</a></td>
                    <td style="text-align: center">{{ $data->insurance_collection_amount }}</td>
                    <td style="text-align: center">{{ $data->billing_company_percent }}</td>
                    <td style="text-align: center">{{ $data->invoice_amount }}</td>
                    <td><a href="../{{ $data->invoice_file }}" target="_blank" >{{ $data->invoice_file }}</a></td>
                    <td>
                        @if ($data->is_approved == 1)
                            <span class="custom-badge status-green" id="approved">{{ "Approved" }}</span>
                       @elseif ($data->is_approved == 0 || $data->is_approved != '')
                        <span class="custom-badge status-red">{{ "Pending" }}</span>
                        @endif
                    </td>
                    @if ($data->billing_id != '')
                    <td style="font-size: 20px; !important;text-align:center">
                      @hasanyrole('admin|Accountant')
                      @if ($data->is_approved == 0)
                      <a type="submit" class="submit" data-submit = "{{ $data->billing_id }}" id="submit_{{ $data->billing_id }}" style="color: rgb(14, 216, 14); cursor: pointer;"><i class="fa fa-check show-icon" ></i></a>@endhasanyrole&nbsp;<a href="{{ url('invoice_details',['id' => $data->invoice_head_id]) }}" class=""><i class="fa fa-eye"></i></a>
                      @endif
                  </td>
                    @endif
                  </tr>
                  @endforeach
  
                </tbody>
              </table>
              </form>
            </div>
          </div>
        </div>
      </div>

    



@endsection

@section('scripts')

<script>
    $(document).ready(function() {
    //     var table = $('#summary_table').DataTable( {
    //     //dom: 'Bfrtip',
    //     // dom: 'lBfrtip',
    //     // responsive: true,
    //     // pageLength: 10,
    //     // lengthChange: true,
    //     // ordering:false,
        
    //     // buttons: [
    //     //     'colvis',
    //     //     'excelHtml5',
    //     //     'csvHtml5',
           
    //     // ]
    //     "paging":   true,
    //     "lengthChange": false,
    //     "ordering": false,
    //     "info":     false,
    //     'searching':false,
    // } );

   

    // table.buttons().container()
    //     .appendTo( '#example_wrapper .small-6.columns:eq(0)' );

        $(".submit").on("click",function(){
              var id = $(this).attr("data-submit");
             

              console.log(id);
              var url = "{{ url('approve_status') }}/"+id;
            $.ajax({
                url: url,
                type: "PUT",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                   
                },
                success: function(data) {
                  console.log("update request success");
                  if (data == 1) {
                   $("#approved").html("Approved");
                   $("#error").html("<div class='alert alert-success' role='alert'>Has been Approved!</div>");
                      
                  }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });

           });
     
});


    
    </script>
@endsection