@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop
@section('title', 'Report')
@section('content')

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
          Submitted with justification : <br>
          <?php $i=1;?>
          @foreach($otdetail as $otd)
            @if( $otr->ot_id == $otd->ot_id && $otd->justification != '')
            <?php echo $i;?>
          .{{$otd->justification }}<br>
            <?php $i++;?>
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
  <div class="form-group text-center">
    <br>
    <button onclick="goBack()" class="btn btn-primary">RETURN</button>
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
      {extend: 'excelHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Log Report', sheetName: 'OT Log',title: 'OT Log Report'},
      {extend: 'pdfHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Log Report', sheetName: 'OT Log',title: 'OT Log Report'},
  		{extend: 'colvis', collectionLayout: 'fixed three-column'}]
  });
});
</script>
@stop
