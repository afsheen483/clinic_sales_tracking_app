{{-- \resources\views\users\index.blade.php --}}
@extends('layouts.master')

@section('title', ' Contact Lens Tracker')

@section('content')

<div class="card">
    <div class="card-header">
      <h4 class="card-title">
        Contact Lens Tracker
    </h4>
    </div>
   
    <div class="card-body">
      <div class="table-responsive">
        <div class="container-fluid">
         
            <table  class="table" style="width:100%">
              <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient Name</th>
                    <th>Location</th>
                    <th>Contact Lens Name</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($data as $data)
                   <tr>
                    <td>{{ date('m/d/y',strtotime($data->invoice_date)) }}</td>
                      <td>{{ $data->patient_firstname }}{{ " " }}{{ $data->patient_lastname }}</td>
                      <td>{{ $data->location }}</td>
                      <td>{{ $data->contact_lens_name }}</td>
                      <td>{{ $data->quantity }}</td>
                      <td>{{ $data->pro_unit_price  }}</td>
                      <td>
                        @if ($data->contact_lens_status == 'Dispensed' || $data->contact_lens_status == 'Not Ordered')
                        <span class="custom-badge status-red" onclick="lens_status_toggle({{ $data->id }})" id="label_lens_{{ $data->id }}" style="cursor: pointer">{{ $data->contact_lens_status }}</span>
                        @else   
                        <span class="custom-badge status-green" onclick="lens_status_toggle({{ $data->id }})" id="label_lens_{{ $data->id }}" style="cursor: pointer">{{ $data->contact_lens_status }}</span>
                     
                        @endif
                        <select name="contact_lens_status" id="select_lens_{{ $data->id }}" onchange="lens_status(this.value,{{ $data->id }})" style="display: none" class="form-control col-5">
                         @foreach ($contact_lens_status_array as $data)
                             <option value="{{ $data->id }}">{{ $data->status }}</option>
                         @endforeach
                        </select>
                      </td>
                     
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

   

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
    
        
        
    function lens_status_toggle(id){
          $("#select_lens_"+id).show();
          $("#label_lens_"+id).hide();
        }
        function lens_status(contact_lens_status,id){
          console.log(lens_status);
                    $("#label_lens_"+id).text($("#select_lens_"+id).find(":selected").text());
                    $("#select_lens_"+id).hide();
                    $("#label_lens_"+id).show();
                    var url = "{{ url('lens_status_update') }}/" + id;
                    
                    $.ajax({
                        url: url,
                        type: "PUT",
                        cache: false,
                        headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                        data: {
                            _token: '{{ csrf_token() }}',
                            contact_lens_status: contact_lens_status
                        },
                        success: function(response) {
                            console.log("success");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("update request failure");
                            //errorFunction(); 
                        }
                    });
        }
    
    </script>
@endsection