@extends('layouts.master')

@section('title')
    Payment Methods
@endsection

@section('headername')
    Payment Methods
@endsection

@section('content')
    

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
      <h5 class="title">Payment Methods</h5>
      <a href="{{ route('payments.index') }}" class="btn btn-primary" style="float: right">Back</a>

    </div>
    <div class="card-body">
@if (request()->id == 0)
      <form method="POST" action="{{ route('payments.store')}}">
      @csrf
@else
    <form method="POST" action="{{ url('payments_update',['id'=>$payments->id])}}">
     @csrf
     @method('PUT')
@endif
            <div class="container">
                <div class="row">
                    <div class="col-md-3 p1-3">
                      <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" placeholder="Enter Method here" name="title" value="{{ (is_null($payments->title)) ? '' : $payments->title}}" required>
                      </div>
                    </div>               
                </div>
          
                    <div class="form-group col-md-3 p1-3">
                      <button type="submit" class="btn btn-primary btn-md">Submit</button>    
                  
                    </div>
        </div>
      </form>
    </div>
  </div>

</div>





    
@endsection

@section('scripts')
    
@endsection