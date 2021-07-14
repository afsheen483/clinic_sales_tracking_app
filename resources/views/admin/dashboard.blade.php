
@extends('layouts.master')


@section('headername')
    Dashboard
@endsection
@section('title')
    Dashboard
@endsection

@section('content')
<div class="row">
@hasanyrole('Staff|Manager|admin')
  <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg1"><i class="fal fa-sticky-note"></i></span>
        <div class="dash-widget-info text-right">
            {{-- <h3> --}}
            @php
                $id = Auth::user()->id;
                //dd($id);
            @endphp
            {{-- @if (Auth::user()->hasrole('vendor'))
                {{ \App\Models\VendorNumberModel::where('order_status','Not Shipped')->where('vendor_id',$id)->get()->count() }}
            @else
            {{ \App\Models\VendorNumberModel::where('order_status','Not Shipped')->get()->count() }}
            @endif</h3> --}}
            <h3><i class="fal fa-sticky-note"></i></h3>
            <span class="widget-title1"><a href="{{  route('patient_checkout_form.create',['id'=>'0']) }}" style="color: rgb(250, 249, 249);">Checkout Patient</a></span>
        </div>
    </div>
  </div>

  <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="dash-widget">
          <span class="dash-widget-bg2"><i class="fas fa-check"></i></span>
          <div class="dash-widget-info text-right">
           <h3><i class="fas fa-check"></i></h3>
            {{-- <h3> --}}
                @php
                    $id = Auth::user()->id;
                    //dd($id);
                @endphp
                {{-- @if (Auth::user()->hasrole('vendor'))
                    {{ \App\Models\VendorNumberModel::where('order_status','Shipped')->where('vendor_id',$id)->get()->count() }}
                @else
                {{ \App\Models\VendorNumberModel::where('order_status','Shipped')->get()->count() }}
                @endif</h3> --}}
                
              <span class="widget-title2"><a href="/reports/end_of_the_day/<?php echo date('Y-m-d')?>/1" style="color: rgb(250, 249, 249);">End of Day</a></span>
          </div>
      </div>
  </div>
  @endhasanyrole
  @hasanyrole('Staff|Manager|admin|Accountant')
  <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="dash-widget">
          <span class="dash-widget-bg3"><i class="fas fa-analytics"></i> </span>
          <div class="dash-widget-info text-right">
           
            <h3>
                <i class="fas fa-analytics"></i> </h3>
              <span class="widget-title3"><a href="/report/insurance_payments" style="color: rgb(250, 249, 249);">Insurance Payment</a></span>
          </div>
      </div>
  </div>
  @endhasanyrole
  @hasanyrole('Manager|admin|Accountant')
  <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg4"><i class="fa fa-list-alt" aria-hidden="true"></i></span>
        <div class="dash-widget-info text-right">
        {{-- <h3> --}}
         <h3><i class="fa fa-list-alt" aria-hidden="true"></i></h3>
            @php
                    $id = Auth::user()->id;
                   // dd($id);
                @endphp
                {{-- @if (Auth::user()->hasrole('vendor'))
                    {{ \App\Models\VendorNumberModel::where('order_status','Missing')->where('vendor_id',$id)->get()->count() }}
                @else
                {{ \App\Models\VendorNumberModel::where('order_status','Missing')->get()->count() }}
                @endif</h3> --}}
            <span class="widget-title4"><a href="/accounts_receiveable_summary" style="color: rgb(250, 249, 249);">Accounts Receivable Report </a> </span>
        </div>
    </div>
</div>  

<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg5"><i class="fas fa-file-invoice"></i></span>
        <div class="dash-widget-info text-right">
            <h3><i class="fas fa-file-invoice"></i></h3>
            <span class="widget-title5"><a href="/invoice_menu/all" style="color: rgb(250, 249, 249);">Billing Invoice Report </a></span>
        </div>
    </div>
</div>
@endhasanyrole
@hasanyrole('Manager|admin')
<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg6"><i class="fa fa-tasks"></i></span>
        <div class="dash-widget-info text-right">
            <h3><i class="fa fa-tasks"></i></h3>
            <span class="widget-title6"><a href="/reporting_manager_module" style="color: rgb(250, 249, 249);">Manager Report </a></span>
        </div>
    </div>
</div>
{{-- <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg1"><i class="fa fa-tasks"></i></span>
        <div class="dash-widget-info text-right">
            <h3><i class="fa fa-tasks"></i></h3>
            <span class="widget-title1"><a href="/weekly_report" style="color: rgb(250, 249, 249);">WEEKLY REPORTS </a></span>
        </div>
    </div>
</div> --}}
@endhasanyrole
@hasrole('admin')
<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg2"><i class="fa fa-tasks"></i></span>
        <div class="dash-widget-info text-right">
            <h3><i class="fa fa-tasks"></i></h3>
            <span class="widget-title2"><a href="/doctor_module" style="color: rgb(250, 249, 249);">Doctor Report </a></span>
        </div>
    </div>
</div>
@endhasrole
<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg1"><i class="fas fa-user-md"></i></span>
        <div class="dash-widget-info text-right">
            <h3>  
                {{ \App\Models\User::whereHas(
                    'roles', function($q){
                        $q->where('id','=',6);
                    }
                )->get()->count() }}
              </h3>
            <span class="widget-title1"><a href="/doctors" style="color: rgb(250, 249, 249);">Doctors </a></span>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg3"><i class="fa fa-tasks"></i></span>
        <div class="dash-widget-info text-right">
            <h3>  
                {{ \App\Models\InvoiceHeadModel::where('is_deleted','=',0)->get()->count() }}
              </h3>
            <span class="widget-title3"><a href="/patients_reports" style="color: rgb(250, 249, 249);">All Patients Report </a></span>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg4"><i class="fa fa-tasks"></i></span>
        <div class="dash-widget-info text-right">
            <h3><i class="fa fa-tasks"></i></h3>
            <span class="widget-title4"><a href="/cash_flow_report" style="color: rgb(250, 249, 249);">Cash Flow Report </a></span>
        </div>
    </div>
</div>
<div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
    <div class="dash-widget">
        <span class="dash-widget-bg5"><i class="fa fa-tasks"></i></span>
        <div class="dash-widget-info text-right">
            <h3><i class="fa fa-tasks"></i></h3>
            <span class="widget-title5"><a href="/yearly_tax_report" style="color: rgb(250, 249, 249);">Yearly Tax Report </a></span>
        </div>
    </div>
</div>
</div>



@endsection



@section('scripts')
  <script>
//     $(document).ready(function(){    
//         // window.location = document.getElementById('click_btn').href
//         for (let i = 0; i < 1; i++) {
//             $('#click_btn').get(0).click();
//             break;
            
//         }


// });
      $(function(){
        $('#location option').each(function() {
            var location_name = $(this).val();
           
            if($(this).is(':selected')) 
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
                 console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("update request failure");
                    //errorFunction(); 
                }
            });
           });
    });
  </script>
@endsection
