<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">

@extends('layouts.master')


@section('title')
Clinics 
@endsection

@section('headername')
Clinics
@endsection

@section('content')


  
      <div class="card">
        <div class="card-header">
          <a href="{{ route('clinics.create',['id'=>'0']) }}" class="btn btn-primary" style="float: right">+ Add Clinic</a>

          <h4 class="card-title">Clinics</h4>
        </div>
        
        <div class="card-body">
           
          <div class="table-responsive">
            <div class="container-fluid">
              <table id="clinic_table" class="display" style="width:100%">
                <thead>
                <th>ID</th>
                <th>Clinic Name</th>
                <th>Location</th>
                <th>Address</th>
                <th>Phone No</th>
                <th>Action</th>
              </thead>
              <tbody>
              @foreach ($clinics as $clinic)
                  
                <tr>
                  <td>{{ $clinic->id }}</td>
                  <td>{{ $clinic->clinic_name }}</td>
                  <td>{{ $clinic->location }}</td>
                  <td>{{ $clinic->address }}</td>
                  <td>{{ $clinic->phone_no }}</td>
                  <td><a href="{{ route('clinics.create',['id'=>$clinic->id]) }}" style="color: rgb(8, 155, 74)"><i class="fa fa-edit"></i></a> | <a href="{{ url('clinic_delete',['id'=>$clinic->id]) }}" style="color: red"><i class="fa fa-trash"></i></a>
                
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>

    



@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#clinic_table').DataTable( {
        //dom: 'Bfrtip',
        dom: 'lBfrtip',
        responsive: true,
        pageLength: 10,
        lengthChange: true,
        ordering:false,
        
        buttons: [
            'colvis',
            'excelHtml5',
            'csvHtml5',
            
        ]
    } );
    table.buttons().container()
        .appendTo( '#example_wrapper .small-6.columns:eq(0)' );
} );
    </script>
@endsection