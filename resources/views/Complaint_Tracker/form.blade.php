@extends('layouts.master')

@section('title')
    Patient Complaint
@endsection

@section('headername')
Patient Complaint

@endsection

@section('content')
   
<div class="card">
    <div class="card-header">
              <a href="{{ url()->previous() }}" style="float:right" class="btn btn-primary">Back</a>

      <h5 class="title">Patient Complaint</h5>
    </div>
    <div class="card-body">
     @if (request()->id == 0)
      <form method="POST" action="{{ url('complaint_store')}}">
      @csrf
    @else
    <form method="POST" action="{{ url('complaint_update',['id'=>$data->id])}}">
      @csrf
      @method('PUT')
    @endif
      <div class="container" style="margin-left: 15%">
        <div class="row">
          <div class="col-md-3 p1-5">
            <div class="form-group">
              <label>Firstname</label>
              <input type="text" class="form-control" placeholder="Enter Firstname...." name="firstname" value="{{ (is_null($data->firstname)) ? '' : $data->firstname}}" required>
            </div>
          </div>
          <div class="col-md-3 p1-5">
            <div class="form-group">
                <label for="">Staff Name</label>
                <input type="text" name="staff_name" id="" placeholder="Enter Staff Name...." value="{{ (is_null($data->staff_name)) ? '' : $data->staff_name}}" required class="form-control">
            </div>
          </div>
          
          </div>
          <div class="row">
            <div class="col-md-3 p1-5">
                <div class="form-group">
                    <label for="">Lastname</label>
                    <input type="text" name="lastname" id="" placeholder="Enter Lastname...." value="{{ (is_null($data->lastname)) ? '' : $data->lastname}}" required class="form-control">
                </div>
            </div>
            <div class="col-md-3 p1-5">
                <div class="form-group">
                    <label for="">Location</label>
                    <select name="location_id" id="" class="form-control" required>
                        <option value="">Select</option>
                        @foreach ($clinic_array as $array)
                            @if ($data->location_id == $array->id)
                                <option value="{{ $array->id }}" selected>{{ $array->location }}</option>
                            @else
                                <option value="{{ $array->id }}">{{ $array->location }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
          </div>
         <div class="row">
            <div class="col-md-3 p1-5">
                <div class="form-group">
                    <label for="">Date of Complaint</label>
                    <input type="date" name="date" id="" required class="form-control" value="{{ is_null($data->date_of_complaint) ? '' : $data->date_of_complaint }}">
                </div>
              </div>
              <div class="col-md-3 p1-5">
                <div class="form-group">
                    <label for="">Priority</label>
                    <select name="priority_id" id="" class="form-control" required>
                        <option value="">Select</option>
                        @foreach ($priority_array as $array)
                            @if ($data->priority_id == $array->id)
                                <option value="{{ $array->id }}" selected>{{ $array->priority_type }}</option>
                            @else
                                <option value="{{ $array->id }}">{{ $array->priority_type }}</option>
                            @endif
                            
                        @endforeach
                    </select>
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6 p1-5">
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" id="" cols="30" rows="10" class="form-control">{{ is_null($data->description) ? '' : $data->description }}</textarea>
                </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-6 p1-5">
                <button type="submit" class="btn btn-primary btn-block">Save Patient Complaint</button>
            </div>
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