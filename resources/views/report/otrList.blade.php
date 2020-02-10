@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop
@section('title', 'Report')
@section('content')

<div class="panel panel-default" id="presult">
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
      <th>Day Type</th>
      <th>Transaction Code</th>
      <th>Estimated Amount</th>
      <th>Claim Status</th>
      <th>Charge Type</th>
      <th>Total Hours</th>
      <th>Total Minutes</th>
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
        <td>{{ $otr->user_id }}</td>
        <td>{{ $otr->URecord->name }}</td>
        <td>{{ $otr->URecord->new_ic }}</td>
        <td>{{ $otr->URecord->staffno }}</td>
        <td>{{ $otr->company_id }}</td>
        <td>{{ $otr->URecord->persarea }}</td>
        <td>{{ $otr->URecord->perssubarea }}</td>
        <td>{{ $otr->state_id }}</td>
        <td>{{ $otr->region }}</td>
        <td>{{ $otr->URecord->empgroup }}</td>
        <td>{{ $otr->URecord->empsgroup }}</td>
      <td>
        @if( $otr->URecord->ot_hour_exception == 'X')
        Yes
        @else
        No
        @endif
      </td>
      <td>
        @if( $otr->URecord->ot_hour_exception == 'X')
        @else
        {{ $otr->SalCap()->salary_cap }}
        @endif
      </td>
      <td>{{ $otr->URecord->empstats }}</td>
      <td>{{ $otr->refno }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->date)) }}</td>
      <td>{{ $otr->daytype->description }}</td>
      <td>{{ $otr->wage_type }}</td>
      <td>{{ $otr->amount }}</td>
      <td>{{ $otr->OTStatus()->item3 }}</td>
      <td>{{ $otr->charge_type }}</td>
      <td>{{ $otr->total_hour }}</td>
      <td>{{ $otr->total_minute }}</td>
      <td>{{ date('d-m-Y H:i:s', strtotime($otr->created_at)) }}</td>
      <td>
        @if( $otr->verification_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->verification_date)) }}
        @endif
      </td>
      <td>{{ $otr->verifier_id }}</td>
      <td>
        @if( $otr->approval_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->approval_date)) }}
        @endif
      </td>
      <td>{{ $otr->approver_id }}</td>
      <td>
        @if( $otr->queried_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->queried_date)) }}
        @endif
      </td>
      <td>{{ $otr->querier_id }}</td>
      <td>
        @if( $otr->payment_date == '')
        @else
        {{ date('d-m-Y', strtotime($otr->payment_date)) }}
        @endif
      </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>
  <div class="form-group text-center">
    <br>
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
      {extend: 'excelHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Summary Report', sheetName: 'OT Summary', title: 'OT Summary Report'},
  		{extend: 'colvis', collectionLayout: 'fixed three-column'}]
  });
});
</script>
@stop
