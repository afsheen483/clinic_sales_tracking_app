<!DOCTYPE html>
<html lang="en">


<!-- login23:11-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.min.js"></script>
		<script src="../assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div class="main-wrapper account-wrapper">
        <div class="account-page">
			<div class="account-center">
				<div class="account-box">
                    <select class="form-control" name="clinic_id" id="location" hidden>
                   
                        @foreach ($clinic_array as $clinic)
                       
                        <option value="{{$clinic->id}}">{{$clinic->location}}</option>
                    
                        @endforeach
                </select>
                    <form method="POST" action="{{ route('login') }}" class="user">
                        @csrf
						<div class="account-logo">
                            <a href="/"><img src="../assets/img/favicon.ico" alt=""></a>
                        </div>
                        <div class="form-group">
                            <label>Username or Email</label>
                            <input id="email" type="text" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address...">
                       
                      @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 

                    </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                      @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                    </div>
                        <div class="form-group text-right">
                            <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary account-btn" id="btn">Login</button>
                        </div>
                    </form>
                </div>
			</div>
        </div>
    </div>
    <script src="../assets/js/jquery-3.2.1.min.js"></script>
	<script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/app.js"></script>
</body>


<!-- login23:12-->
<script>
    $(document).ready(function(){
        $("#btn").click(function(){
            $('#location option').each(function() {
            var location_name = $(this).val();
           
            if($(this).is(':selected')) 
            //$.session.set("location","location_name"); 
            //alert(location_name);
            var url = "{{ url('location') }}";
            $.ajax({
                url: url,
                type: "GET",
                cache: false,
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    location_name:location_name,
                },
                success: function(data) {
                 console.log("session_Set"+data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
           });
        });
    });
</script>
</html>