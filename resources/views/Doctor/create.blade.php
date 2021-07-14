{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.master')
<style>
    .error{
        color: red;
    }
</style>
@section('title', 'Add Doctor')

@section('content')

<div class='col-lg-4 col-lg-offset-4'>

    <h1><i class='fa fa-user-plus'></i> Add Doctor</h1>
    <hr>

    {{ Form::open(array('url' => 'doctors','id'=>'registration')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control','required' => '')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::text('email', '', array('class' => 'form-control','required' => '','id'=>'email')) }}
    </div>

    <div class='form-group' style="display: none">
        @foreach ($roles as $role)
        @if ($role->id == 6)
            {{ Form::checkbox('roles[]',  $role->id,true ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>
        @else
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>
        @endif
        @endforeach
    </div>

    <div class="form-group" style="display: none">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}

    </div>

    <div class="form-group" style="display: none">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" type="text/javascript"></script>

<script>
      var email =  $("#email").val();
    $('#registration').validate({
        rules: {            
            email: {
                required: true,
                remote: {
                    url: "{{url('user/checkemail')}}",
                    type: "post",
                    data: {
                        email:$(email).val(),
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