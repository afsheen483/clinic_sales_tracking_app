<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.foundation.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">


@extends('layouts.master')


@section('title')
    Insurance
@endsection

@section('headername')
    Insurance 
@endsection

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <a href="{{ route('insurance.create',['id'=> '0'])}}" class="btn btn-primary" style="float: right">+ Add Insurance</a>

        <h4 class="card-title">Insurance</h4>
      </div>
      
      <div class="card-body">
         
        <div class="table-responsive">
          <div class="container-fluid">
            <table id="insurance_table" class="display" style="width:100%">
              <thead >
              <th>ID</th>
              <th>Insurance Title </th>
              <th>Action</th>
            </thead>
            <tbody>
            @foreach ($insurances as $insurance)
                
              <tr>
                <td>{{ $insurance->id }}</td>
                <td>{{ $insurance->insurance_title }}</td>
                <td><a href="{{ route('insurance.create',['id'=> $insurance->id])}}" style="color: rgb(8, 155, 74)"><i class="fa fa-edit"></i></a> | <a href="{{ url('insurance_delete',['id'=> $insurance->id]) }}" style="color: red;"><i class="fa fa-trash"></i></a>
              </td>
              </tr>
              @endforeach

            </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>


@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script>
    $(document).ready(function() {
     var table = $('#insurance_table').DataTable( {
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
        
       
               
        });
    </script>
@endsection