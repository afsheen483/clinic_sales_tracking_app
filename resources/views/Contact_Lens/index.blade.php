{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.master')

@section('title', ' Contact Lens')

@section('content')

<div class="card">
    <div class="card-header">
      <a href="{{ url('contact_lens_create',['id'=>0]) }}" class="btn btn-primary" style="float: right">+ Add New Contact Lens</a>
      <h4 class="card-title">
       Contact Lens
    </h4>
    </div>
   
    <div class="card-body">
      <div class="table-responsive">
        <div class="container-fluid">
         
            <table  class="table" style="width:100%">
              <thead>
                <tr>
                    <th>ID</th>
                    <th>Contact Lens</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $data)
                   <tr>
                      <td>{{ $data->id }}</td>
                      <td>{{ $data->contact_lens_name }}</td>
                     
                        <td>
                        <a href="contact_lens_create/{{ $data->id }}"><i class="fa fa-edit"></i></a> | 
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
    
        
        
          $(".delete").click(function(){
          var delete_id = $(this).attr("id");
          var th=$(this);
          console.log(delete_id);
          var url = "{{url('contact_lens_delete')}}/"+delete_id;
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

    
    </script>
@endsection