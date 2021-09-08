

  @extends('layouts.master')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  
  @section('title')
  Patient's Detail
  @endsection
  
  @section('headername')
  Patient's Detail
  @endsection
  
  @section('content')
  
  
    
        <div class="card">
          <div class="card-header">
            <a class="btn btn-primary" href = "../accounts_receiveable_summary" style="float: right">Back</a>

            <h3 class="card-title">
              Patient's Detail
            </h3>
             
        
          </div>
          <div class="col-3" style="margin-left: 0.5cm">
            <input type="button" id="btnExport" value=" Excel" class="btn btn-success btn-lg" />
         </div>
          <div class="card-body">
            
            <div class="table-responsive">
              <div class="container-fluid" id="dvData">
               
                 
                  <table id="" class="table">
                    <thead>
                      <tr>
                        <th>Week</th>
                        <th>Year</th>
                        <th>Patient Name</th>
                        <th>Type of Exam</th>
                        <th>Insurance</th>
                        <th>Copay/PP</th>
                        <th>Insurance/PP</th>
                        <th>Copay Collection</th>
                        <th>Doctor</th>
                       <!--  <th>Status</th> -->
                        <th> Transaction 
                          Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($reports as $data)
                      <tr>
                        <td>{{ $data->week_number }}</td>
                        <td>{{ $data->year }}</td>
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
                       <!--  <td>
                          @if ($data->paid == 1)
                          <span class="custom-badge status-green" id="paid">{{ "Paid" }}</span>
                          @else
                          <span class="custom-badge status-red" id="unpaid">{{ "Unpaid" }}</span>
                          @endif
                        </td> -->
                        <td>
                          @if ($data->is_completed == 1 )
                          <span class="custom-badge status-green" id="approved">{{ "Complete " }}</span>
                          
                      @else
                        <span class="custom-badge status-red" id="approved">{{ "Incomplete " }}</span>
                      @endif
                        </td>
                        <td><a href="{{ route('patient_checkout_form.create',['id'=> $data->id]) }}" style="color: rgb(8, 155, 74)"><i class="fa fa-edit"></i></a> | <a  style="color: red;cursor: pointer;" id="{{ $data->id }}" data-delete="{{ $data->id }}" class="delete_btn"><i class="fa fa-trash"></i></a>

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

  $(document).ready(function(){
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
  });

  </script>
  @endsection