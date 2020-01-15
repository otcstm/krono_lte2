@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop
@section('title', 'Report')
@section('content')
<div class="panel panel-default">
<div class="panel-heading"><strong>Report : Overtime Log Changes</strong></div>
<div class="panel-body">
  <form action="{{ route('otr.viewOTLog', [], false) }}" method="post">
  @csrf
  <div class="col-sm-6">
  <div class="form-group">
    	<label for="fdate">From</label>
  	<input type="date" class="form-control" id="fdate" name="fdate" required autofocus>
  </div>
  </div>
  <div class="col-sm-6">
  <div class="form-group">
  	<label for="tdate">To</label>
  	<input type="date" class="form-control" id="tdate" name="tdate"  required autofocus>
  </div>
  </div>
  <div class="col-sm-6">
  <div class="form-group">
  	<label for="fpersno">Persno</label>
  	<input type="text" class="form-control" id="fpersno" name="fpersno">
  </div>
  </div>
  <div class="col-sm-6">
  <div class="form-group">
  	<label for="frefno">Refno</label>
  	<input type="text" class="form-control" id="frefno" name="frefno">
  </div>
  </div>
  <div class="col-sm-12">
  <div class="form-group text-center">
    <input type="hidden" name="searching" value="log">
    <button type="submit" class="btn btn-primary">Search</button>
  </div>
  </div>
  </form>
</div>
</div>

@if($vlist == true)
<div class="panel panel-default">
<div class="panel-heading panel-primary">List of Log Changes</div>
<div class="panel-body">
  <div class="table-responsive">
  <table id="tOtlist" class="table table-bordered">
    <thead>
      <tr>
      <th>Reference Number</th>
      <th>Personnel Number</th>
      <th>Employee Name</th>
      <th>IC Number</th>
      <th>Staff ID</th>
      <th>Action Date</th>
      <th>Action Time</th>
      <th>Action By</th>
      <th>Action Log</th>
      <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      @foreach($otrep as $otr)
      <tr>
      <td>{{ $otr->detail->refno }}</td>
      <td>{{ $otr->detail->user_id }}</td>
      <td>{{ $otr->detail->URecord->name }}</td>
      <td>{{ $otr->detail->URecord->new_ic }}</td>
      <td>{{ $otr->detail->URecord->staffno }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->created_at)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->created_at)) }}</td>
      <td>{{ $otr->user_id }}</td>
      <td>{{ $otr->action }}</td>
      <td>
        @if( $otr->action == 'Submitted')
          Submitted with justification <br>
          @foreach($otdetail as $otd)
            @if( $otr->ot_id == $otd->ot_id && $otd->justification != '')
            -{{ $otd->justification }}<br>
            @endif
          @endforeach
        @else
        {{ $otr->message }}
        @endif
      </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  </div>
</div>
</div>
@endif


@if(session()->has('feedback'))
<div id="feedback" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <div class="glyphicon glyphicon-{{session()->get('feedback_icon')}}" style="color: {{session()->get('feedback_color')}}; font-size: 32px;"></div>
        <p>{{session()->get('feedback_text')}}<p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
      </div>
    </div>
  </div>
</div>
@endif

@stop
@section('js')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {
  $('#tOtlist').DataTable({
    "responsive": "true",
    "order" : [[0, "asc"]],
    dom: 'Bfrtip',
		buttons: [
    {
      extend: 'excelHtml5',
      exportOptions: {
        columns: ':visible'
      }
    },
    {
      extend: 'pdfHtml5',
      exportOptions: {
        columns: ':visible'
      }
    },
		{
        extend: 'colvis',
        collectionLayout: 'fixed three-column'
    }
	  ]
  });
});


@if(session()->has('feedback'))
    $('#feedback').modal('show');
@endif
</script>
@stop
