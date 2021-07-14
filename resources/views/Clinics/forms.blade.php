@extends('layouts.master')

@section('title')
    Create Clinics
@endsection

@section('headername')
 Clinics
@endsection

@section('content')
    

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
      <h5 class="title">Clinic</h5>
      <a href="{{ route('clinics.index') }}" class="btn btn-primary" style="float: right">Back</a>

    </div>
    <div class="card-body">
@if (request()->id == 0)
      <form method="POST" action="{{ route('clinics.store')}}">
      @csrf
@else
    <form method="POST" action="{{ url('clinic_update',['id'=>$clinics->id])}}">
     @csrf
     @method('PUT')
@endif
            <div class="container">
                <div class="row">
                    <div class="col-md-3 p1-3">
                      <div class="form-group">
                        <label>Clinic Name</label>
                        <input type="text" class="form-control" placeholder="Enter Clinic Name here" name="clinic_name" value="{{ (is_null($clinics->clinic_name)) ? '' : $clinics->clinic_name}}" required>
                      </div>
                      
                      
                    </div>
                    <div class="col-md-3 p1-3">
                      <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="form-control" placeholder="Enter Clinic Location here" name="location" value="{{ (is_null($clinics->location)) ? '' : $clinics->location}}" required>
                      </div>
                    </div>                  
                    </div>          
                    <div class="row">
                      <div class="col-md-3 p1-3">
                          <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" placeholder="Enter Clinic Address here" name="address" value="{{ (is_null($clinics->address)) ? '' : $clinics->address}}" required>
                          </div>                          
                        </div>
                        <div class="col-md-3 p1-3">
                          <div class="form-group">
                            <label>Phone No</label>
                            <input type="tel" class="form-control" placeholder="Enter Clinic PhoneNo here" name="phone_no" value="{{ (is_null($clinics->phone_no)) ? '' : $clinics->phone_no}}" required>
                          </div> 
                        </div>
                    </div>
                    <div class="form-group col-md-3 p1-3">
                      <button type="submit" class="btn btn-primary btn-md">Submit</button>    
                  
                    </div>
            </div>
        </div>
      </form>
    </div>
  </div>

</div>





    
@endsection

@section('scripts')
    
@endsection