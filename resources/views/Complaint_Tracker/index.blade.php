{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.master')

@section('title', 'Patient Complaint Tracker')

@section('content')

<div class="card">
    <div class="card-header">
      <a href="{{ url('complaint_create',['id'=>0]) }}" class="btn btn-primary" style="float: right">+ Add New Complaint</a>
      <h4 class="card-title">
       Patient Complaint Tracker
    </h4>
    </div>
   
    <div class="card-body">
      <div class="table-responsive">
        <div class="container-fluid">
         
            <table  class="table" style="width:100%">
              <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date Of Complaint</th>
                    <th>Location</th>
                    <th>Staff</th>
                    <th>Priority</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Manager Initials</th>
                    <th>Manager Comments</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $data)
                    @if ($data->priority_status == 'High')
                    <tr style="background-color: rgb(240, 93, 93)">
                    @elseif ($data->priority_status == 'Medium')
                    <tr style="background-color: yellow">
                    @elseif ($data->priority_status == 'Low')
                    <tr style="background-color: rgb(255,255,153)">
                    @elseif ($data->track_status == 'Complete')
                    <tr style="background-color: green">
                    @endif
                      <td>{{ $data->firstname }}{{ " " }}{{ $data->lastname }}</td>
                      <td>{{ date('m/d/y',strtotime($data->date_of_complaint)) }}</td>
                      <td>{{ $data->location }}</td>
                      <td>{{ $data->staff_name }}</td>
                      <td>{{ $data->priority_status }}</td>
                      <td>{{ $data->description }}</td>
                      <td>
                        @if ($data->track_status == 'Unresolved')
                        <span data-track="{{ $data->id }}" onclick="track_status_toggle({{ $data->id }})" style="cursor: pointer" id="lable_status_{{ $data->id }}" class=" custom-badge status-red">{{ $data->track_status }}</span>

                        @else
                        <span data-track="{{ $data->id }}" onclick="track_status_toggle({{ $data->id }})" style="cursor: pointer" id="lable_status_{{ $data->id }}" class=" custom-badge status-green">{{ $data->track_status }}</span>

                        @endif
                        <select name="track_status" id="select_status_{{ $data->id }}" onchange="change_status(this.value,{{ $data->id }})" style="display: none" class="form-control">
                          @foreach ($track_status_array as $status)
                              <option value="{{ $status->id }}">{{ $status->status_type }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td   data-init="{{ $data->id }}" contenteditable="true" id="init_{{ $data->id }}" class="init">{{ $data->manager_initials }}</td>
                      <td   data-comment="{{ $data->id }}" contenteditable="true" id="comment_{{ $data->id }}" class="comment">{{ $data->comments_from_manager }}</td>
                      <td>
                        <a href="complaint_create/{{ $data->id }}"><i class="fa fa-edit"></i></a> | 
                        <a id="{{ $data->id }}" style="color: red;cursor: pointer;" class="delete"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

   

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function track_status_toggle(id){
          $("#select_status_"+id).show();
          $("#lable_status_"+id).hide();
        }
        function change_status(track_status,id){
          console.log(track_status);
                    $("#lable_status_"+id).text($("#select_status_"+id).find(":selected").text());
                    $("#select_status_"+id).hide();
                    $("#lable_status_"+id).show();
                    var url = "{{ url('track_status_update') }}/" + id;
                    
                    $.ajax({
                        url: url,
                        type: "PUT",
                        cache: false,
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        data: {
                            _token: '{{ csrf_token() }}',
                            track_status: track_status
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
        $(document).ready(function(){
          $(".init").on("focusout",function(){
            var id = $(this).attr("data-init");
            console.log(id);
            var val = $("#init_"+id).text();
            console.log(val);
            var url = "{{ url('manager_initials') }}/" + id;
                    
                    $.ajax({
                        url: url,
                        type: "PUT",
                        cache: false,
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        data: {
                            _token: '{{ csrf_token() }}',
                            val: val
                        },
                        success: function(response) {
                            console.log("success");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("update request failure");
                            //errorFunction(); 
                        }
                    });

          });

          $(".comment").on("focusout",function(){
            var id = $(this).attr("data-comment");
            console.log(id);
            var val = $("#comment_"+id).text();
            console.log(val);
            var url = "{{ url('manager_comments') }}/" + id;
                    
                    $.ajax({
                        url: url,
                        type: "PUT",
                        cache: false,
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        data: {
                            _token: '{{ csrf_token() }}',
                            val: val
                        },
                        success: function(response) {
                            console.log("success");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("update request failure");
                            //errorFunction(); 
                        }
                    });

          });
          $(".delete").click(function(){
          var delete_id = $(this).attr("id");
          var th=$(this);
          console.log(delete_id);
          var url = "{{url('complaint_delete')}}/"+delete_id;
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