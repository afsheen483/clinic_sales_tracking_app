@extends('layouts.master')

@section('title')
    Services
@endsection

@section('headername')
Services
@endsection

@section('content')
    

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
      <h5 class="title">Services</h5>
      <a href="{{ route('service.index') }}" class="btn btn-primary" style="float: right">Back</a>

    </div>
    <div class="card-body">
@if (request()->id == 0)
      <form method="POST" action="{{ route('service.store')}}">
      @csrf
@else
    <form method="POST" action="{{ url('service_update',['id'=>$services->id])}}">
     @csrf
     @method('PUT')
@endif
            <div class="container">
                <div class="row">
                    <div class="col-md-3 p1-3">
                      <div class="form-group">
                        <label>Service Title</label>
                        <input type="text" class="form-control" placeholder="Enter Title here" name="service_title" value="{{ (is_null($services->title)) ? '' : $services->title}}">
                      </div>
                      
                      
                    </div>
                    <div class="col-md-3 p1-3">
                      <div class="form-group">
                        <label>Unit Price</label>
                        <input type="number" class="form-control" placeholder="Enter Unit Price here" name="unit_price" value="{{ (is_null($services->unit_price)) ? '' : $services->unit_price}}">
                      </div>
                    </div>                  
                    </div>          
                    <div class="row">
                      <div class="col-md-3 p1-3">
                          <div class="form-group">
                              @if ($services->is_insured == 1)
                              <input type="checkbox"   name="is_insured" checked value="1">
                              @else
                              <input type="checkbox"   name="is_insured" value="1">

                              @endif
                            <label>is Insured</label>&nbsp;&nbsp;&nbsp;
                            @if ($services->is_product == 1)
                            <input type="checkbox" name="is_product" id="" checked value="1">
                            @else
                            <input type="checkbox" name="is_product" id="" value="1">
                            @endif
                            <label for="">is Product</label>
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