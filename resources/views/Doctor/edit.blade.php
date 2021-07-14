{{-- \resources\views\users\edit.blade.php --}}

@extends('layouts.master')
<style>
    .error{
        color: red;
    }
</style>
@section('title', 'Edit Doctor')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$user->name}}</h1>
    <hr>

    {{ Form::model($user, array('route' => array('doctors.update', $user->id), 'method' => 'PUT','id' => 'registration')) }}{{-- Form model binding to automatically populate our fields with user data --}}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control','required' => '')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email', null, array('class' => 'form-control email','required' => '','id'=>$user->id)) }}
    </div>

    {{-- <h5><b>Give Role</b></h5> --}}

    <div class='form-group' style="display: none" >
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
    </div>

    <div class="form-group" style="display: none">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}

    </div>

    <div class="form-group" style="display: none">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation',array('class' => 'form-control')) }}

    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" type="text/javascript"></script>

<script>
      var email =  $(".email").val();
      var id = $(".email").attr('id');
      console.log(id);
    $('#registration').validate({
        rules: {            
            email: {
                required: true,
                remote: {
                    url: "{{url('edit/checkemail')}}",
                    type: "post",
                    data: {
                        email:email,
                        id:id,
                        _token:"{{ csrf_token() }}"
                        },
                    dataFilter: function (data) {
                        var json = JSON.parse(data);
                        console.log(data);
                        if (json.msg == "true") {
                            return "\"" + "Email address already in use!" + "\"";
                        } else {
                            return 'true';
                        }
                    }
                }
            }
        },
        messages: {            
            email: {
                required: "Email is required!",
                remote: "Email address already in use!"
            }
        }
    });
</script>
@endsection