@extends('layouts.master')

@section('title')
    Contact Lens
@endsection

@section('headername')
Contact Lens

@endsection

@section('content')
   
<div class="card">
    <div class="card-header">
        <a href="{{ url()->previous() }}" style="float:right" class="btn btn-primary">Back</a>
        <h3 class="title">Contact Lens</h3>
    </div>
    <div class="card-body">
     @if (request()->id == 0)
      <form method="POST" action="{{ url('contact_lens_store')}}">
      @csrf
    @else
    <form method="POST" action="{{ url('contact_lens_update',['id'=>$data->id])}}">
      @csrf
      @method('PUT')
    @endif
      <div class="container" style="margin-left: 15%">
        <div class="row">
          <div class="col-md-3 p1-5">
            <div class="form-group">
              <label>Contact Lens</label>
              <input type="text" class="form-control" placeholder="Enter Contact Lens...." name="contact_lens_name" value="{{ (is_null($data->contact_lens_name)) ? '' : $data->contact_lens_name}}" required>
            </div>
          </div>
          </div>
          
          
        
         
         <div class="row">
            <div class="col-md-3 p1-5">
                <button type="submit" class="btn btn-primary btn-block">Save Contact Lens</button>
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