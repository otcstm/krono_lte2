@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop
@section('title', 'Report')
@section('content')

<div class="panel panel-default">
<div class="panel-heading panel-primary">List of OT</div>
<div class="panel-body">
  <div class="table-responsive">
  <table id="tOtlist" class="table table-bordered">
    <thead>
      <tr>
      <th>Personnel Number</th>
      <th>Employee Name</th>
      <th>IC Number</th>
      <th>Staff ID</th>
      <th>Company Code</th>
      <th>Personnel Area</th>
      <th>Personnel Subarea</th>
      <th>State</th>
      <th>Region</th>
      <th>Employee Group</th>
      <th>Employee Subgroup</th>
      <th>Salary Exception</th>
      <th>Capping Salary (RM)</th>
      <th>Employment Status</th>
      <th>Reference Number</th>
      <th>OT Date</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Manual Flag</th>
      <th>Day Type</th>
      <th>Location</th>
      <th>Transaction Code</th>
      <th>Estimated Amount (RM)</th>
      <th>Claim Status</th>
      <th>Charge Type</th>
      <th>Number of Hours</th>
      <th>Number of Minutes</th>
      <th>Justification</th>
      <th>Application Date</th>
      <th>Verification Date</th>
      <th>Verifier</th>
      <th>Approval Date</th>
      <th>Approver</th>
      <th>Queried Date</th>
      <th>Queried By</th>
      <th>Payment Date</th>
      </tr>
    </thead>
    <tbody>
      @foreach($otrep as $otr)
      <tr>
        <td>{{ $otr->mainOT->user_id }}</td>
        <td>{{ $otr->mainOT->URecord->name }}</td>
        <td>{{ $otr->mainOT->URecord->new_ic }}</td>
        <td>{{ $otr->mainOT->URecord->staffno }}</td>
        <td>{{ $otr->mainOT->company_id }}</td>
        <td>{{ $otr->mainOT->URecord->persarea }}</td>
        <td>{{ $otr->mainOT->URecord->perssubarea }}</td>
        <td>{{ $otr->mainOT->state_id }}</td>
        <td>{{ $otr->mainOT->region }}</td>
        <td>{{ $otr->mainOT->URecord->empgroup }}</td>
        <td>{{ $otr->mainOT->URecord->empsgroup }}</td>
      <td>
        @if( $otr->mainOT->URecord->ot_hour_exception == 'X')
        Yes
        @else
        No
        @endif
      </td>
      <td>
        @if( $otr->mainOT->URecord->ot_hour_exception == 'X')
        @else
        {{ $otr->mainOT->SalCap()->salary_cap }}
        @endif
      </td>
      <td>{{ $otr->mainOT->URecord->empstats }}</td>
      <td>{{ $otr->mainOT->refno }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->mainOT->date)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->start_time)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->end_time)) }}</td>
      <td>{{ $otr->is_manual }}</td>
      <td>{{ $otr->mainOT->daytype->description }}</td>
      <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
      <td>{{ $otr->mainOT->wage_type }}</td>
      <td>{{ $otr->amount }}</td>
      <td>{{ $otr->mainOT->OTStatus()->item3 }}</td>
      <td>{{ $otr->mainOT->charge_type }}</td>
      <td>{{ $otr->hour }}</td>
      <td>{{ $otr->minute }}</td>
      <td>{{ $otr->justification }}</td>
      <td>
        @if( $otr->mainOT->created_at == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->created_at)) }}
        @endif
      </td>
      <td>
        @if( $otr->mainOT->verification_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->verification_date)) }}
        @endif
      </td>
      <td>{{ $otr->mainOT->verifier_id }}</td>
      <td>
        @if( $otr->mainOT->approval_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->approval_date)) }}
        @endif
      </td>
      <td>{{ $otr->mainOT->approver_id }}</td>
      <td>
        @if( $otr->mainOT->queried_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->queried_date)) }}
        @endif
      </td>
      <td>{{ $otr->mainOT->querier_id }}</td>
      <td>
        @if( $otr->mainOT->payment_date == '')
        @else
        {{ date('d-m-Y', strtotime($otr->mainOT->payment_date)) }}
        @endif
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
