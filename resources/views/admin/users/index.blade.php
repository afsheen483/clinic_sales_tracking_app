{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.master')

@section('title', 'Users')

@section('content')

<div class="col-lg-12 col-lg-offset-1">
    <h1><i class="fa fa-users"></i> User Administration <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>
    <a href="{{ route('permissions.index') }}" class="btn btn-default pull-right">Permissions</a></h1>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Username or Email</th>
                    <th>Date/Time Added</th>
                    <th>User Roles</th>
                    <th>Operations</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
                    <td>{{  $user->roles()->pluck('name')->implode(' ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                    <td>
                    <a href="users_edit/{{ $user->id }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    @if($user->is_activate == 1)
                         <button  class="btn btn-success pull-left check_btn" style="margin-right: 3px;" data-status = "{{ $user->id}}">Activate</button>
                @else
                     <button  class="btn btn-danger pull-left check_btn" style="margin-right: 3px;" data-status = "{{ $user->id}}">Deactivate</button>   
                @endif

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>

</div>

@endsection
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function(){
             $('.check_btn').on('click',function () {
          var id = $(this).data("status");
          console.log(id);
          var url = "{{url('active_status_user')}}/"+id;
         
                      $.ajax({
                      
                        url : url,
                        type : 'PUT',
                        cache: false,
                        data: {_token:'{{ csrf_token() }}'},
                        success:function(data){
                         if (data == 1) {
                          Swal.fire({
                              
                                      position: 'top-end',
                                      icon: 'success',
                                      title: 'User has been deactivated successfully!',
                                      showConfirmButton: false,
                                      timer: 5000
                        })
                              location.reload();
                         }if(data == 0){
                            Swal.fire({
                                position: 'top-end',
                                      icon: 'success',
                                      title: 'User has been activated successfully!',
                                      showConfirmButton: false,
                                      timer: 5000
                              })
                              location.reload();
                         }
                        
                        }
              
              });
               
        });
        });
    </script>
@endsection

