@extends('layouts.master')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@section('title')
    Patient Checkout Form
@endsection

@section('headername')
 Patient Checkout Form
 @endsection

@section('content')
   
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
         
            <a href="{{ url()->previous() }}" class="btn btn-primary" style="float: right">Back</a>
            <h5 class="title"> Patient Checkout Form</h5>
            </div>
    <div class="card-body">
@if (request()->id == 0 )
      <form method="POST" action="{{ route('patient_checkout_form.store') }}">
      @csrf
@elseif(request()->id > 0 && url()->previous() == url('/report/insurance_payments'))
    <form method="POST" action="{{ url('patient_update',['id'=>$invoice_patient_data[0]->id,'pre'=>'insurance_payments'])}}">
     @csrf
     @method('PUT')
     
@elseif(request()->id > 0 && url()->previous() == url('/patients_reports') || url()->previous() == url('/transaction_report/all') || url()->previous() == url('/transaction_report/completed') ||url()->previous() == url('/transaction_report/incompleted'))
     <form method="POST" action="{{ url('patient_update',['id'=>$invoice_patient_data[0]->id,'pre'=>'patients_reports'])}}">
      @csrf
      @method('PUT')
     @else
    <form method="POST" action="{{ url('patient_update',['id'=>$invoice_patient_data[0]->id,'pre'=>'end_of_day'])}}">
     @csrf
     @method('PUT')
@endif
        @hasrole('Manager')
                <div class="row">
                    <div class="col-2">
                            <div class="form-group">
                               
                                <label for="">Location</label>
                                    <select class="form-control" name="clinic_id" id="">
                                       
                                            @foreach ($clinic_array as $clinic)
                                            @if ($invoice_patient_data[0]->clinic_id == $clinic->id)
                                                <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                                            @elseif (session()->get('location_name') == $clinic->id )
                                                <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                                            @else
                                                <option value="{{$clinic->id}}">{{$clinic->location}}</option>
                                            @endif
                                            @endforeach
                                    </select>
                            </div> 
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">Doctor</label>
                                <select class="form-control" name="doctor_id" id="" >
                                    <option value="">Select Doctor Name</option>
                                        @foreach ($doctor_user_array as $doctor)
                                        @if ($invoice_patient_data[0]->doctor_id == $doctor->id)
                                            <option value="{{$doctor->id}}" selected>{{$doctor->name}}</option>
                                        @else
                                            <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                                        @endif
                                        @endforeach
                                </select>
                        </div> 
                </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">Date</label>
                            @if (request()->id == 0)
                                <input type="date" class="form-control" name="invoice_date" id="" value="<?php echo date('Y-m-d');?>">
                            @else
                                <input type="date" class="form-control" name="invoice_date" id="" value="{{ $invoice_patient_data[0]->invoice_date }}" >
                            @endif
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">Firstname</label>
                            <input type="text" class="form-control" name="patient_firstname"  placeholder="Enter Patient Firstname" value="{{ (is_null($invoice_patient_data[0]->patient_firstname)) ? '' : $invoice_patient_data[0]->patient_firstname}}" >
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">Lastname</label>
                            <input type="text" class="form-control" name="patient_lastname" id=""  placeholder="Enter Patient Lastname" value="{{ (is_null($invoice_patient_data[0]->patient_lastname)) ? '' : $invoice_patient_data[0]->patient_lastname}}">
                        </div>
                    </div>
                </div> 
                <div class="row" style="margin-left:0.1cm;margin-top:0.1cm;">
                    <div class="form-group" style="margin-top:0.4cm;">
                    @if ($invoice_patient_data[0]->is_out_of_pocket == 1)
                        <input type="checkbox"  name="is_out_of_pocket" id="is_out_of_pocket"  placeholder="" value="1" checked >
                        <label for="">Out Of Pocket</label>
                    @else
                        <input type="checkbox"  name="is_out_of_pocket" id="is_out_of_pocket"  placeholder="" value="1" >
                        <label for="">Out Of Pocket</label>
                    @endif
                    
                     </div>
                
                   <div class="col-2">
                        <div class="form-group">
                                <select class="form-control" name="primary_insurance_id" id="primary_insurance_payment" >
                                    <option value="">Select Primary Insurance</option>
                                        @foreach ($insurance_array as $insurance)
                                        @if ($invoice_patient_data[0]->primary_insurance_id == $insurance->id)
                                            <option value={{$insurance->id}} selected>{{$insurance->insurance_title}}</option>
                                        @else
                                            <option value={{$insurance->id}}>{{$insurance->insurance_title}}</option>
                                        @endif
                                        @endforeach
                                </select>
                         </div>
                   </div>
                   <div class="col-2">
                        <div class="form-group">
                                <select class="form-control" name="secondary_insurance_id" id="secondary_insurance_payment">
                                    <option value="">Select Secondary Insurance</option>
                                        @foreach ($insurance_array as $insurance)
                                        @if ($invoice_patient_data[0]->secondary_insurance_id == $insurance->id)
                                            <option value={{$insurance->id}} selected>{{$insurance->insurance_title}}</option>
                                        @else
                                            <option value={{$insurance->id}}>{{$insurance->insurance_title}}</option>
                                        @endif
                                        @endforeach
                                </select>
                         </div>
                   </div>
                  
                </div>
               

                <div class="row">
                    <div class="container">
                        <table  border= "1px solid black;" cellspacing="0" >
                       
                       
                            <tr style="font-weight: bold">
                                <td rowspan="5">Type of Service</td>
                                <td>Service</td>
                                <td>Copayment</td>
                                <td>Insurance Payment</td>
                            </tr>
                      
                            @php
                                $invoice_data = 0;
                            @endphp
                            @foreach ($service_array as $service)
                                <tr>
                                    <td>
                                         {{$service->title}}
                                    </td>
                                    <td>
                                        <input name="invoice_detail_id_{{ $service->id }}" value="{{ (is_null($invoice_head_data[0]->id)) ? '' : $invoice_head_data[$invoice_data]->id}}"  hidden>
                                        <input type="text" class="form-control col-6 copayment" name="copayment_{{ $service->id }}" id="copayment_{{ $service->id }}" placeholder="$" value="{{ (is_null($invoice_head_data[0]->copayment)) ? '' : $invoice_head_data[$invoice_data]->copayment}}" style="margin-left: 1.7cm" disabled>
                                    </td>

                                  
                                    <td>
                                        <input name="invoice_detail_id_{{ $service->id }}" value="{{ (is_null($invoice_head_data[0]->id)) ? '' : $invoice_head_data[$invoice_data]->id}}" hidden>
                                        @if (request()->id > 0)
                                        <input type="text" class="form-control col-6 insurance_payment" name="insurance_payment_{{ $service->id }}" data-insurance="{{ $service->id }}" id="insurance_payment_{{ $service->id }}" placeholder="$" value="{{ $invoice_head_data[$invoice_data]->insurance_payment}}" style="margin-left: 1.7cm">
                                    @else
                                        <input type="text" class="form-control col-6 insurance_payment" name="insurance_payment_{{ $service->id }}" data-insurance="{{ $service->id }}" id="insurance_payment_{{ $service->id }}" placeholder="$"  style="margin-left: 1.7cm">
        
                                    @endif                                    </td>
                                   
                                </tr> 
                               @php
                                    $invoice_data++;
                               @endphp
                            @endforeach
                           
                               
                                    <td></td>
                                    <td></td>
                                    <td rowspan="11" align="center">
                                        <textarea name="remarks" id="" cols="30" rows="20" placeholder="Remarks" required disabled>{{ (is_null($invoice_patient_data[0]->remarks)) ? '' : $invoice_patient_data[0]->remarks}}</textarea>
                                        @if (request()->id == 0  && $invoice_patient_data[0]->is_completed == 1)
                                        <button type="button" class="btn btn-danger col-12 btn block" disabled>Mark InComplete</button> 
                                        
                                        @elseif (request()->id == 0 && $invoice_patient_data[0]->is_completed == 0 || $invoice_patient_data[0]->is_completed == NULL)
                                        {{-- <button type="button" class="btn btn-success col-12 btn block submit" id="is_completed_{{ $invoice_patient_data[0]->id }}" data-submit="{{ $invoice_patient_data[0]->id }}">Complete Transaction</button> --}}
                                        <button type="button" class="btn btn-success col-12 btn block" disabled>Mark Complete</button>
                
                
                                        {{-- <button type="button" class="btn btn-danger col-12 btn block incomplete" id="in_is_completed_{{ $invoice_patient_data[0]->id }}" data-incomplete="{{ $invoice_patient_data[0]->id }}">InComplete Transaction</button> --}}
                                        @endif
                                    
                                        @if ($invoice_patient_data[0]->is_completed == 0 || $invoice_patient_data[0]->is_completed == NULL)
                                            <span class="col-12 custom-badge status-red" style="height: 30px; padding-top:4px;">{{ "Status:Incomplete " }}</span>
                                        @else
                                        <span class="col-12 custom-badge status-green" style="height: 30px; padding-top:4px;">{{ "Status:complete" }}</span>
                                        @endif
                                    </td>
                                    
                               
                        <tr>
                            <td rowspan="10" style="font-weight: bold">Extra Services</td>
                        </tr>
                            
                            @foreach ($extra_service_array as $ext_service)
                            <tr>
                                <td >
                                     {{$ext_service->title}}
                                </td>
                                <td>
                                    <input name="invoice_detail_id_{{ $ext_service->id }}" value="{{ (is_null($invoice_head_data[0]->id)) ? '' : $invoice_head_data[$invoice_data]->id}}"  hidden disabled>

                                    <input type="text" class="form-control col-6 extra_copayment" name="extra_copayment_{{ $ext_service->id }}" id="extra_copayment_{{ $ext_service->id }}" disabled placeholder="$" value="{{ (is_null($invoice_head_data[0]->copayment)) ? '' : $invoice_head_data[$invoice_data]->copayment}}" style="margin-left: 1.7cm">
                                </td>
                                
                               
                            </tr> 
                            @php
                                $invoice_data++;
                            @endphp
                        @endforeach
                       
                       
                        <tr style="font-weight: bold">
                            <td rowspan="4">Product</td>                        
                        </tr>
                        @foreach ($product_array as $service)
                        <tr>
                            <td >
                                 {{$service->title}}
                            </td>
                            <td class="multiply">
                                <div class="row" style="margin-left:18%">
                                    <input name="invoice_detail_id_{{ $service->id }}" value="{{ (is_null($invoice_head_data[0]->id)) ? '' : $invoice_head_data[$invoice_data]->id}}"  hidden>
                                      <input type="number"
                                        class="form-control col-3 quantity" name="quantity_{{ $service->id }}" id="quantity_{{ $service->id }}" aria-describedby="helpId" placeholder="QTY" data-quantity = "{{$service->id}}" value="{{ (is_null($invoice_head_data[0]->quantity)) ? '' : $invoice_head_data[$invoice_data]->quantity}}" disabled>
                                      <input type="number"
                                        class="form-control col-3 unit_price" name="unit_price_{{ $service->id }}" id="unit_price_{{ $service->id }}" aria-describedby="helpId" placeholder="Price" data-unit = "{{ $service->id }}" value="{{ (is_null($invoice_head_data[0]->pro_unit_price)) ? '' : $invoice_head_data[$invoice_data]->pro_unit_price}}" disabled>
                                        <span id="quant_unit" style="margin-left: 0.7cm; margin-top:0.2cm;font-weight:bold"></span>
                                    </div>
                            </td>
                            <td></td>
                            
                        </tr>
                        @php
                            $invoice_data++;
                        @endphp
                    @endforeach
                    
                    <tr style="font-weight: bold">
                        <td colspan="2">Total</td>
                        <td ><span style="margin-left: 1.7cm" id="total_copayment">$</span></td>
                        <td><span id="sum_insurance_payment" style="margin-left: 1.7cm">$</span></td> 
                    </tr>
                    <tr style="font-weight: bold">
                        <td rowspan="5">Payment Method<td>
                     </tr>       
                     @php
                            $invoice_data = 0;
                        @endphp
                     @foreach ($payment_method_array as $payment)
                     <tr>
                           <td>{{$payment->title}}</td>
                           <td>
                                
                                 <input type="text"
                             class="form-control col-6 payment_methods" name="payment_method_{{ $payment->id }}" id="payment_methods"  placeholder="$" style="margin-left: 1.7cm" disabled value="{{ (is_null($invoice_payment_data[0]->amount)) ? '' : $invoice_payment_data[$invoice_data]->amount}}">
                           </td>
                           <td></td>
                     </tr>
                     @php
                            $invoice_data++;
                        @endphp
                 @endforeach
                 <tr style="font-weight: bold">
                    <td colspan="2">Balance</td>
                    <td><span id="total_payment_balance" style="margin-left: 1.7cm">$</span></td>
                    <td></td>
                 </tr>
                 <tr style="font-weight: bold">
                    <td colspan="2">Insurance Balance</td>
                    <td><input type="text" name="insurance_balance" id="insurance_balance" placeholder="$" class="form-control col-6" style="margin-left: 1.7cm" value="{{ $invoice_patient_data[0] == null ? '$' : $invoice_patient_data[0]->insurance_balance }}" disabled></td>
                    <td>Total Balance: <span id="Total_balance">{{ $invoice_patient_data[0] == null ? '$' : $invoice_patient_data[0]->total_balance }}</span><input type="text" name="total_balance" id="total_bal" value="{{ $invoice_patient_data[0] == null ? '$' : $invoice_patient_data[0]->total_balance }}" hidden></td>
                </tr>
                    </table>
                    <br>
                    <div class="row" >
                        <div class="col-3" style="margin-left:17cm;margin-top:0.1cm">
                            <div class="form-group">
                            @if ($invoice_patient_data[0]->family_upsell == 1)
                                <input type="checkbox"  name="family_upsell" id=""  placeholder="" value="1" checked disabled>
                                <label for="">Family Upsell</label>
                            @else
                                <input type="checkbox"  name="family_upsell" id=""  placeholder="" value="1" disabled>
                                <label for="">Family Upsell</label>
                            @endif
                            </div>
                        </div>
                        <div class="col-2" style="margin-left:20.3cm;margin-top:-1.4cm">
                            <button type="submit" id="submit_button" class="btn btn-primary btn-block" >Submit</button>
                    
                        </div>
                    </div>
                    </div>
                   
                </div>
               
                
             
          
      </form>
    </div>
  </div>

</div>

@else
<div class="row">
    <div class="col-2">
            <div class="form-group">
                
                <label for="">Location</label>
                    <select class="form-control" name="clinic_id" id="" required>
                       
                            @foreach ($clinic_array as $clinic)
                            @if ($invoice_patient_data[0]->clinic_id == $clinic->id)
                                <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                            @elseif (session()->get('location_name') == $clinic->id )
                                <option value="{{$clinic->id}}" selected>{{$clinic->location}}</option>
                            @else
                                <option value="{{$clinic->id}}">{{$clinic->location}}</option>
                            @endif
                            @endforeach
                    </select>
            </div> 
    </div>
    <div class="col-2">
        <div class="form-group">
            <label for="">Doctor</label>
                <select class="form-control" name="doctor_id" id="" required>
                    <option value="">Select Doctor Name</option>
                        @foreach ($doctor_user_array as $doctor)
                        @if ($invoice_patient_data[0]->doctor_id == $doctor->id)
                            <option value="{{$doctor->id}}" selected>{{$doctor->name}}</option>
                        @else
                            <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                        @endif
                        @endforeach
                </select>
        </div> 
</div>
    <div class="col-2">
        <div class="form-group">
            <label for="">Date</label>
            @if (request()->id == 0)
                 <input type="date" class="form-control" name="invoice_date" id="" value="<?php echo date('Y-m-d');?>" >
            @else
                 <input type="date" class="form-control" name="invoice_date" id="" value="{{ $invoice_patient_data[0]->invoice_date }}" >
            @endif
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <label for="">Firstname</label>
            <input type="text" class="form-control" name="patient_firstname"  placeholder="Enter Patient Firstname" value="{{ (is_null($invoice_patient_data[0]->patient_firstname)) ? '' : $invoice_patient_data[0]->patient_firstname}}"  required>
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <label for="">Lastname</label>
            <input type="text" class="form-control" name="patient_lastname" id=""  placeholder="Enter Patient Lastname" value="{{ (is_null($invoice_patient_data[0]->patient_lastname)) ? '' : $invoice_patient_data[0]->patient_lastname}}" required>
        </div>
    </div>
</div> 
<div class="row" style="margin-left:0.1cm;margin-top:0.1cm;">
    <div class="form-group" style="margin-top:0.4cm;">
    @if ($invoice_patient_data[0]->is_out_of_pocket == 1)
        <input type="checkbox"  name="is_out_of_pocket" id="is_out_of_pocket"  placeholder="" value="1" checked >
        <label for="">Out Of Pocket</label>
    @else
        <input type="checkbox"  name="is_out_of_pocket" id="is_out_of_pocket"  placeholder="" value="1" >
        <label for="">Out Of Pocket</label>
    @endif
    
     </div>

   <div class="col-2">
        <div class="form-group">
                <select class="form-control primary_insurance_payment" name="primary_insurance_id" id="primary_insurance_payment" >
                    <option value="">Select Primary Insurance</option>
                        @foreach ($insurance_array as $insurance)
                        @if ($invoice_patient_data[0]->primary_insurance_id == $insurance->id)
                            <option value={{$insurance->id}} selected>{{$insurance->insurance_title}}</option>
                        @else
                            <option value={{$insurance->id}}>{{$insurance->insurance_title}}</option>
                        @endif
                        @endforeach
                </select>
         </div>
   </div>
   <div class="col-2">
        <div class="form-group">
                <select class="form-control  secondary_insurance_payment" name="secondary_insurance_id" id="secondary_insurance_payment" >
                    <option value="">Select Secondary Insurance</option>
                        @foreach ($insurance_array as $insurance)
                        @if ($invoice_patient_data[0]->secondary_insurance_id == $insurance->id)
                            <option value={{$insurance->id}} selected>{{$insurance->insurance_title}}</option>
                        @else
                            <option value={{$insurance->id}}>{{$insurance->insurance_title}}</option>
                        @endif
                        @endforeach
                </select>
         </div>
   </div>
  
</div>


<div class="row">
    <div class="container">
        <table  border= "1px solid black;" cellspacing="0" >
            <tbody>
                <tr style="font-weight: bold">
                    <td rowspan="5">Type of Service</td>
                    <td>Service</td>
                    <td>Copayment</td>
                    <td>Insurance Payment</td>
                    </tr>
                    @php
                    $invoice_data = 0;
                    @endphp
                     @foreach ($service_array as $service)
                     <tr>
                         <td>
                              {{$service->title}}
                         </td>
                         <td>
                             <input name="invoice_detail_id_{{ $service->id }}" value="{{ (is_null($invoice_head_data[0]->id)) ? '' : $invoice_head_data[$invoice_data]->id}}"  hidden>
                             @if (request()->id > 0)
                                 <input type="text" class="form-control col-6 copayment" name="copayment_{{ $service->id }}" id="copayment_{{ $service->id }}" placeholder="$" value="{{ $invoice_head_data[$invoice_data]->copayment}}" style="margin-left: 1.7cm" >
                             @else
                                 <input type="text" class="form-control col-6 copayment" name="copayment_{{ $service->id }}" id="copayment_{{ $service->id }}" placeholder="$"  style="margin-left: 1.7cm" >
                             @endif
                         </td>
             
                       
                         <td>
                            @if (request()->id > 0)
                                <input type="text" class="form-control col-6 insurance_payment" name="insurance_payment_{{ $service->id }}" data-insurance="{{ $service->id }}" id="insurance_payment_{{ $service->id }}" placeholder="$" value="{{ $invoice_head_data[$invoice_data]->insurance_payment}}" style="margin-left: 1.7cm">
                            @else
                                <input type="text" class="form-control col-6 insurance_payment" name="insurance_payment_{{ $service->id }}" data-insurance="{{ $service->id }}" id="insurance_payment_{{ $service->id }}" placeholder="$"  style="margin-left: 1.7cm">

                            @endif
                             {{-- <input type="text" class="form-control col-6" name="insurance_payment_{{ $service->id }}" style="margin-left: 1.7cm" hidden> --}}
                            <span class="in"></span>
                        </td>
                        
                     </tr> 
                    @php
                         $invoice_data++;
                    @endphp
                 @endforeach
                     <tr>
                         <td></td>
                         <td></td>
                         <td rowspan="11">
               
                            <textarea name="remarks" id="" cols="30" rows="20" placeholder="Remarks"  >{{ (is_null($invoice_patient_data[0]->remarks)) ? '' : $invoice_patient_data[0]->remarks}}</textarea>
                        @if (request()->id == 0  && $invoice_patient_data[0]->is_completed == 1)
                        <button type="button" class="btn btn-danger col-12 btn block" disabled>Mark InComplete</button> 
                        
                        @elseif (request()->id == 0 && $invoice_patient_data[0]->is_completed == 0)
                        {{-- <button type="button" class="btn btn-success col-12 btn block submit" id="is_completed_{{ $invoice_patient_data[0]->id }}" data-submit="{{ $invoice_patient_data[0]->id }}">Complete Transaction</button> --}}
                        <button type="button" class="btn btn-success col-12 btn block" disabled>Mark Complete</button>


                        {{-- <button type="button" class="btn btn-danger col-12 btn block incomplete" id="in_is_completed_{{ $invoice_patient_data[0]->id }}" data-incomplete="{{ $invoice_patient_data[0]->id }}">InComplete Transaction</button> --}}
                        @endif
                        @if (request()->id > 0 && $invoice_patient_data[0]->is_completed == 1)
                        <button type="button" class="btn btn-danger col-12 btn block incomplete" id="in_is_completed_{{ $invoice_patient_data[0]->id }}" data-incomplete="{{ $invoice_patient_data[0]->id }}">Mark InComplete</button>

                        

                        
                        @elseif (request()->id > 0 && $invoice_patient_data[0]->is_completed == 0)
                        <button type="button" class="btn btn-success col-12 btn block submit" id="is_completed_{{ $invoice_patient_data[0]->id }}" data-submit="{{ $invoice_patient_data[0]->id }}">Mark Complete</button>
                        
                        @endif
                        <br>
                        @if ($invoice_patient_data[0]->is_completed == 0)
                            {{-- <span class="col-12 custom-badge status-red" style="height: 30px; padding-top:4px;"></span> --}}
                            <div class="alert alert-danger col-12" role="alert" style="height: 50px; ">
                                {{ "Status:    Incomplete" }}
                              </div>
                            @else
                        {{-- <button type="button" class="btn btn-danger col-12 btn block incomplete" id="in_is_completed_{{ $invoice_patient_data[0]->id }}" data-incomplete="{{ $invoice_patient_data[0]->id }}">InComplete Transaction</button> --}}

                        <div class="alert alert-success col-12" role="alert" style="height: 50px; ">
                            {{ "Status:   Complete" }}
                          </div>
                        @endif
                        </td>
                      
                     </tr>
                   
                   
                     
                  
                  
                        <tr></tr>
                       <tr><td rowspan="10" style="font-weight: bold">Extra Services</td></tr>
                    
               
                @foreach ($extra_service_array as $ext_service)
                <tr>
                    <td>
                         {{$ext_service->title}}
                    </td>
                    <td>
                        <input name="invoice_detail_id_{{ $ext_service->id }}" value="{{ (is_null($invoice_head_data[0]->id)) ? '' : $invoice_head_data[$invoice_data]->id}}"  hidden >
                        @if (request()->id > 0)
                            <input type="text" class="form-control col-6 extra_copayment" name="extra_copayment_{{ $ext_service->id }}" id="extra_copayment_{{ $ext_service->id }}"  placeholder="$" value="{{ $invoice_head_data[$invoice_data]->copayment}}" style="margin-left: 1.7cm">
                        @else
                             <input type="text" class="form-control col-6 extra_copayment" name="extra_copayment_{{ $ext_service->id }}" id="extra_copayment_{{ $ext_service->id }}"  placeholder="$"  style="margin-left: 1.7cm">
                        @endif
                    </td>
                   
                </tr> 
                @php
              
                    $invoice_data++;
                  
                @endphp
            @endforeach
            <tr style="font-weight: bold">
                <td rowspan="4">Product</td>
            
            </tr>
            @foreach ($product_array as $service)
            <tr>
                <td >
                     {{$service->title}}
                </td>
                <td class="multiply">
                    <div class="row" style="margin-left:18%">
                        <input name="invoice_detail_id_{{ $service->id }}" value="{{ (is_null($invoice_head_data[0]->id)) ? '' : $invoice_head_data[$invoice_data]->id}}"  hidden>
                        @if (request()->id > 0)
                            <input type="number" class="form-control col-3 quantity" name="quantity_{{ $service->id }}" id="quantity_{{ $service->id }}" aria-describedby="helpId" placeholder="QTY" data-quantity = "{{$service->id}}" value="{{ $invoice_head_data[$invoice_data]->quantity}}" min="0" >
                            <input type="number" class="form-control col-3 unit_price" name="unit_price_{{ $service->id }}" id="unit_price_{{ $service->id }}" aria-describedby="helpId" placeholder="Price" data-unit = "{{ $service->id }}" value="{{ $invoice_head_data[$invoice_data]->pro_unit_price}}" min="0">
                        @else
                        <input type="number" class="form-control col-3 quantity" name="quantity_{{ $service->id }}" id="quantity_{{ $service->id }}" aria-describedby="helpId" placeholder="QTY" data-quantity = "{{$service->id}}"  min="0" >
                        <input type="number" class="form-control col-3 unit_price" name="unit_price_{{ $service->id }}" id="unit_price_{{ $service->id }}" aria-describedby="helpId" placeholder="Price" data-unit = "{{ $service->id }}"  min="0">
                        @endif
                            <span id="quant_unit" style="margin-left: 0.7cm; margin-top:0.2cm;font-weight:bold"></span>
                        </div>
                </td>
                <td></td>
                
            </tr>
            @php
                $invoice_data++;
            @endphp
            @endforeach
            
            <tr style="font-weight: bold">
            <td colspan="2">Total</td>
            <td ><span style="margin-left: 1.7cm" id="total_copayment">$</span></td>
            <td><span id="sum_insurance_payment" style="margin-left: 1.7cm">$</span></td> 
            </tr> 
            <tr style="font-weight: bold">
            <td rowspan="5">Payment Method<td>
            
            </tr>      
            @php
                $invoice_data = 0;
            @endphp
            @foreach ($payment_method_array as $payment)
            <tr>
               <td>{{$payment->title}}</td>
               <td>
                    @if (request()->id > 0)
                        <input type="text" class="form-control col-6 payment_methods" name="payment_method_{{ $payment->id }}"  placeholder="$" style="margin-left: 1.7cm"  value="{{ $invoice_payment_data[$invoice_data]->amount}}">
                    @else
                        <input type="text" class="form-control col-6 payment_methods" name="payment_method_{{ $payment->id }}"  placeholder="$" style="margin-left: 1.7cm" >
                    @endif
              </td>
               <td></td>
            </tr>
            @php
                $invoice_data++;
            @endphp
            @endforeach
            <tr style="font-weight: bold">
            <td colspan="2">Balance</td>
            <td><span id="total_payment_balance" style="margin-left: 1.7cm">$</span><input type="text" id="cop_bal"  value="" hidden></td>
            <td></td>
            </tr>
               <tr style="font-weight: bold">
                   <td colspan="2">Insurance Balance</td>
                   <td><input type="text" name="insurance_balance" id="insurance_balance" placeholder="$" class="form-control col-6" style="margin-left: 1.7cm" value="{{ $invoice_patient_data[0] == null ? '$' : $invoice_patient_data[0]->insurance_balance }}"></td>
                   <td>Total Balance: <span id="Total_balance">{{ $invoice_patient_data[0] == null ? '$' : $invoice_patient_data[0]->total_balance }}</span><input type="text" name="total_balance" id="total_bal" value="{{ $invoice_patient_data[0] == null ? '$' : $invoice_patient_data[0]->total_balance }}" hidden></td>
               </tr>
            </tbody>
        </table>
    <br>
    <div class="row" >
        <div class="col-3" style="margin-left:17cm;margin-top:0.1cm">
            <div class="form-group">
            @if ($invoice_patient_data[0]->family_upsell == 1)
                <input type="checkbox"  name="family_upsell" id=""  placeholder="" value="1" checked >
                <label for="">Family Upsell</label>
            @else
                <input type="checkbox"  name="family_upsell" id=""  placeholder="" value="1" >
                <label for="">Family Upsell</label>
            @endif
            </div>
        </div>
        <div class="col-2" style="margin-left:22.3cm;margin-top:-1.4cm">
            <button type="submit" id="submit_button" class="btn btn-primary btn-block submit_button" >Submit</button>
    
        </div>
    </div>
    
    </div>
   
</div>




</form>
</div>
</div>

</div>

@endhasrole




    
@endsection

@section('scripts')

    <script>
        
        $(document).ready(function(){
             
                
           $('.copayment,.extra_copayment,.insurance_payment,.payment_methods,.quantity,.unit_price,#insurance_balance').keyup(function () {
                 var sum = 0;
                 var difference = 0;
                 
            $('.copayment,.extra_copayment').each(function() {
                sum += Number($(this).val());
            });
            // console.log(sum);
             $("#total_copayment").html(sum);

       
       
                 var total_insurance = 0;
            $('.insurance_payment').each(function() {
                total_insurance += Number($(this).val());
                
                   

        
            // console.log(total);
             $("#sum_insurance_payment").html("$"+total_insurance);

             var mult = 0;
             $("td.multiply").each(function(){
                var quantity = $('.quantity', this).val();
                var unit_price = $('.unit_price', this).val();
                var total_multiply = (quantity * 1) * (unit_price * 1);
                $('#total_copayment',this).text(total_multiply);
                $('#quant_unit',this).text("$"+total_multiply);

                mult += total_multiply;
             });
                var grand_total = 0;
                    grand_total = sum + mult ;
                   // $("#total_copayment").text(mult);
                    $("#total_copayment").html("$"+grand_total);
                   

                    var payment_sum = 0;

                $(".payment_methods").each(function(){
                    payment_sum += Number($(this).val());
                    
                });
                var balance = 0;
                balance = grand_total - payment_sum;

                $("#total_payment_balance").html("$"+balance);
                $("#cop_bal").val(balance);
                    
                    var val = $("#insurance_balance").val();
                    var t_insurance_balance = Number(balance) + Number(val);
                    $("#Total_balance").html("$"+t_insurance_balance);
                    $("#total_bal").val(t_insurance_balance);



 });
               
        }).keyup();
        
       

    });
    $(function() {
        
  $('#is_out_of_pocket').change(function() {
    var id = $(this).attr("data-insurance");
    if ($(this).is(':checked')) {
        // disable the dropdown:
        $('#secondary_insurance_payment, #primary_insurance_payment').attr('disabled', 'disabled');
        $('#primary_insurance_payment').removeAttr('required');
        $(".insurance_payment").attr('readonly', 'readonly');

    } else {
        $('#secondary_insurance_payment, #primary_insurance_payment').removeAttr('disabled');
        $('#primary_insurance_payment').attr('required', 'required');
        $('.insurance_payment').removeAttr('readonly');
        
    }
  });
  if($('#is_out_of_pocket').is(':checked')){
        $('#secondary_insurance_payment, #primary_insurance_payment').attr('disabled', 'disabled');
        $('#primary_insurance_payment').removeAttr('required');
        $(".insurance_payment").attr('readonly', 'readonly');
       



    }
    else {
        $('#secondary_insurance_payment, #primary_insurance_payment').removeAttr('disabled');
        $('#primary_insurance_payment').attr('required', 'required');
        $('.insurance_payment').removeAttr('readonly');

    }

  
  $(".submit").on("click",function(){
      var id = $(this).attr("data-submit");
      console.log(id);
    var url = "{{ url('completed_status') }}/"+id;
            $.ajax({
                url: url,
                type: "PUT",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                   

                },
                success: function(data) {
                    console.log(data);
                    if(data == 1){
                    Swal.fire(
                        'Congratulations!',
                        'Your Transaction has been completed!',
                        'success'
                    ) 
                   

                    }
                   
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
  });


//   incomplete
$(".incomplete").on("click",function(){
      var id = $(this).attr("data-incomplete");
      console.log(id);
    var url = "{{ url('incompleted_status') }}/"+id;
            $.ajax({
                url: url,
                type: "PUT",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                   

                },
                success: function(data) {
                    console.log(data);
                    if(data == 1){
                    Swal.fire(
                        'Congratulations!',
                        'Your Transaction has been Incompleted!',
                        'success'
                    ) 
                   

                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
  });

//   var str = $("#total_copayment").text();
//   var value = str.split("$");
//   //alert(value[1]);
//   if (value[1] == 0) {
//    $('#submit_button').attr('disabled', 'disabled');

//   }else{
//       $("#submit_button").removeAttr('disabled');
//   }
//   $('.copayment,.extra_copayment,.insurance_payment,.payment_methods,.quantity,.unit_price').keyup(function () {
//     var str = $("#total_copayment").text();
//   var value = str.split("$");
//     if (value[1] == 0) {
//    $('#submit_button').attr('disabled', 'disabled');
    

//   }else{
//       $("#submit_button").removeAttr('disabled');
//   }
//    // $("#submit_button").removeAttr('disabled');
//   });
  //alert(value[1]);
    // $("#secondary_insurance_payment, #primary_insurance_payment").each(function(){
    //     if($('#is_out_of_pocket').is(':checked') ||   $(this).is(':selected') ){
    //         ("#submit_button").removeAttr('disabled');
    //     }else{
    //         $('#submit_button').attr('disabled', 'disabled');
    //        // alert('else');
    //     }

    // });

//     $("#submit_button").click(function(){
//         Swal.fire(
   
// )
// if ( window.history.replaceState ) {
//   window.history.replaceState( null, null, window.location.href );
// }
   



   if($(".insurance_payment").prop('disabled') == true){
       alert('yes');
}
});

        
    </script>
@endsection