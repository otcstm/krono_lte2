@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop
@section('title', 'Report')
@section('content')


<div class="panel panel-default">
<div class="panel-heading panel-primary">List of Start/End OT Time</div>
<div class="panel-body">
  <div class="table-responsive">
  <table id="tOtlist" class="table table-bordered">
    <thead>
      <tr>
      <th>Pers Number</th>
      <th>Employee Name</th>
      <th>IC Number</th>
      <th>Staff ID</th>
      <th>Comp Code</th>
      <th>Pers Area</th>
      <th>Pers Subarea</th>
      <th>State</th>
      <th>Region</th>
      <th>Emp Group</th>
      <th>Emp Subgroup</th>
      <th>Date</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Day Type</th>
      <th>Location</th>
      <th>Apply OT Claim?</th>
      </tr>
    </thead>
    <tbody>
      @foreach($otrep as $otr)
      <tr>
        <td>{{ $otr->user_id }}</td>
        <td>{{ $otr->URecord->name }}</td>
        <td>{{ $otr->URecord->new_ic }}</td>
        <td>{{ $otr->URecord->staffno }}</td>
        <td>{{ $otr->URecord->company_id }}</td>
        <td>{{ $otr->URecord->persarea }}</td>
        <td>{{ $otr->URecord->perssubarea }}</td>
        <td>{{ $otr->URecord->state_id }}</td>
        <td>{{ $otr->URecord->Reg->region }}</td>
        <td>{{ $otr->URecord->empgroup }}</td>
        <td>{{ $otr->URecord->empsgroup }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->punch_in_time)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->punch_in_time)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->punch_out_time)) }}</td>
      <td>{{ $otr->day_type}}</td>
      <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
      <td>
        {{$otr->ot_applied}}

      </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="form-group text-center"><br>
    <button onclick="goBack()" class="btn btn-primary">Go Back</button>
  </div>
</div>
</div>

@stop
@section('js')
<script>
function goBack() {
  window.history.back();
}
</script>
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
    {extend: 'excelHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
    {extend: 'pdfHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
		{extend: 'colvis', collectionLayout: 'fixed three-column'}]
  });
});
</script>
@stop
