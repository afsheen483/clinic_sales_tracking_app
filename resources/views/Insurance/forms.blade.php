@extends('layouts.master')

@section('title')
    Insurance
@endsection

@section('headername')
   Form Insurance

@endsection

@section('content')
   
<div class="card">
    <div class="card-header">
      <h5 class="title">Insurance</h5>
      <a href="{{ route('insurance.index') }}" style="float:right" class="btn btn-primary">Back</a>
    </div>
    <div class="card-body">
     @if (request()->id == 0)
      <form method="POST" action="{{ route('insurance.store')}}">
      @csrf
    @else
    <form method="POST" action="{{ url('insurance_update',['id'=>$insurance->id])}}">
      @csrf
      @method('PUT')
    @endif
      <div class="container" style="margin-left: 10%">
        <div class="row">
          <div class="col-md-4 p1-5">
            <div class="form-group">
              <label>Insurance</label>
              <input type="text" class="form-control" placeholder="Enter Insurance Title here" name="insurance_title" value="{{ (is_null($insurance->insurance_title)) ? '' : $insurance->insurance_title}}" required>
            </div>
            <div class="form-group col-md-3 ">
                <button type="submit" class="btn btn-primary btn-md">Submit</button>    
            
              </div>
            
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