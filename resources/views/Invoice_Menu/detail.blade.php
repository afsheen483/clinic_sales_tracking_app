

@extends('layouts.master')


@section('title')
    Patient Invoice Detail
@endsection

@section('headername')
    Patient Invoice Detail
@endsection

@section('content')


  
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">
            Patient Invoice Detail
        </h4>
        </div>
        
        <div class="card-body">
        
          <div class="table-responsive">
            <div class="container-fluid">
             
                <table id="" class="table">
                  <thead>
                   <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Location</th>
                    <th>Patient Name</th>
                    <th>Type of Exam</th>
                    <th>Insurance</th>
                    <th>Copay/PP</th>
                    <th>Insurance/PP</th>
                    <th>Copay Collection</th>
                    <th>Doctor</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($invoice_data_details as $data)
                    
                <tr>
                    <td>{{ date('m/d/y',strtotime($data->invoice_date)) }}</td>
                    <td>{{ date('l',strtotime($data->invoice_date)) }}</td>
                    <td>{{ $data->location}}</td>
                    <td>{{ $data->patient_firstname }}{{"  "}}{{$data->patient_lastname}}</td>
                    {{-- <td>{{ $data->title }}</td> --}}
                   
                    <td>{{ $data->title }}</td>
                    <td>@if ($data->p_insurance_title != '' ||$data->s_insurance_title != '')
                      {{ $data->p_insurance_title }}{{ ", " }}{{ $data->s_insurance_title }}
                    @else
                    {{ "" }}
                    @endif</td>
                    <td class="copay">{{ $data->total_amount }}</td>
                    <td>{{ $data->insurance_payment }}</td>
                    <td>{{ $data->payment_title }}</td>
                    <td>{{ $data->name }}</td>
                    <td>
                      <a href="{{ route('patient_checkout_form.create',['id'=> $data->invoice_head_id]) }}" style="color: rgb(8, 155, 74)"><i class="fa fa-edit"></i></a> 
                    </td>
                </tr>
                  @endforeach
  
                </tbody>
              </table>
              </form>
            </div>
          </div>
        </div>
      </div>

    



@endsection

@section('scripts')





@endsection