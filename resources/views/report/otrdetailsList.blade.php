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
      <th>Reference Number</th>
      <th>OT Date</th>
      <th>Start Time</th>
      <th>End Time</th>
      @if($cbcolumn ?? '')
      @foreach($cbcolumn as $col)
      @if( $col == 'psarea')
      <th>Personnel Area</th>
      @elseif( $col == 'psbarea')
      <th>Personnel Subarea</th>
      @elseif( $col == 'state')
      <th>State</th>
      @elseif( $col == 'region')
      <th>Region</th>
      @elseif( $col == 'empgrp')
      <th>Employee Group</th>
      @elseif( $col == 'empsubgrp')
      <th>Employee Subgroup</th>
      @elseif( $col == 'salexp')
      <th>Salary Exception</th>
      @elseif( $col == 'capsal')
      <th>Capping Salary (RM)</th>
      @elseif( $col == 'empst')
      <th>Employment Status</th>
      @elseif( $col == 'mflag')
      <th>Manual Flag</th>
      @elseif( $col == 'dytype')
      <th>Day Type</th>
      @elseif( $col == 'loc')
      <th>Location</th>
      @elseif( $col == 'trnscd')
      <th>Transaction Code</th>
      @elseif( $col == 'estamnt')
      <th>Estimated Amount</th>
      @elseif( $col == 'clmstatus')
      <th>Claim Status</th>
      @elseif( $col == 'chrtype')
      <th>Charge Type</th>
      @elseif( $col == 'noh')
      <th>Number of Hours</th>
      @elseif( $col == 'nom')
      <th>Number of Minutes</th>
      @elseif( $col == 'jst')
      <th>Justification</th>
      @elseif( $col == 'appdate')
      <th>Application Date</th>
      @elseif( $col == 'verdate')
      <th>Verification Date</th>
      @elseif( $col == 'verid')
      <th>Verifier</th>
      @elseif( $col == 'appdate')
      <th>Approval Date</th>
      @elseif( $col == 'apprvrid')
      <th>Approver</th>
      @elseif( $col == 'qrdate')
      <th>Queried Date</th>
      @elseif( $col == 'qrdby')
      <th>Queried By</th>
      @elseif( $col == 'pydate')
      <th>Payment Date</th>
      @endif
      @endforeach
      @endif
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
        <td>{{ $otr->mainOT->refno }}</td>
        <td>{{ date('d-m-Y', strtotime($otr->mainOT->date)) }}</td>
        <td>{{ date('H:i:s', strtotime($otr->start_time)) }}</td>
        <td>{{ date('H:i:s', strtotime($otr->end_time)) }}</td>
        @if($cbcolumn ?? '')
        @foreach($cbcolumn as $col)
          @if( $col == 'psarea')
          <td>{{ $otr->mainOT->URecord->persarea }}</td>
          @elseif( $col == 'psbarea')
          <td>{{ $otr->mainOT->URecord->perssubarea }}</td>
          @elseif( $col == 'state')
          <td>{{ $otr->mainOT->state_id }}</td>
          @elseif( $col == 'region')
          <td>{{ $otr->mainOT->region }}</td>
          @elseif( $col == 'empgrp')
          <td>{{ $otr->mainOT->URecord->empgroup }}</td>
          @elseif( $col == 'empsubgrp')
          <td>{{ $otr->mainOT->URecord->empsgroup }}</td>
            @elseif( $col == 'salexp')
          <td>
          @if( $otr->mainOT->URecord->ot_salary_exception == 'X')
          Yes
          @else
          No
          @endif
          </td>
          @elseif( $col == 'capsal')
          <td>
          @if( $otr->mainOT->URecord->ot_salary_exception == 'X')
          @else
          {{ $otr->mainOT->SalCap()->salary_cap }}
          @endif
          </td>
          @elseif( $col == 'empst')
          <td>{{ $otr->mainOT->URecord->empstats }}</td>
          @elseif( $col == 'mflag')
          <td>{{ $otr->is_manual }}</td>
          @elseif( $col == 'dytype')
          <td>{{ $otr->mainOT->daytype->description }}</td>
          @elseif( $col == 'loc')
          <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
          @elseif( $col == 'trnscd')
          <td>{{ $otr->mainOT->wage_type }}</td>
          @elseif( $col == 'estamnt')
          <td>{{ $otr->amount }}</td>
          @elseif( $col == 'clmstatus')
          <td>{{ $otr->mainOT->OTStatus()->item3 }}</td>
          @elseif( $col == 'chrtype')
          <td>{{ $otr->mainOT->charge_type }}</td>
          @elseif( $col == 'noh')
          <td>{{ $otr->hour }}</td>
          @elseif( $col == 'nom')
          <td>{{ $otr->minute }}</td>
          @elseif( $col == 'jst')
          <td>{{ $otr->justification }}</td>
          @elseif( $col == 'appdate')
          <td>
          @if( $otr->mainOT->created_at == '')
          @else
          {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->created_at)) }}
          @endif
          </td>
          @elseif( $col == 'verdate')
          <td>
          @if( $otr->mainOT->verification_date == '')
          @else
          {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->verification_date)) }}
          @endif
          </td>
          @elseif( $col == 'verid')
          <td>{{ $otr->mainOT->verifier_id }}</td>
          @elseif( $col == 'appdate')
          <td>
          @if( $otr->mainOT->approval_date == '')
          @else
          {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->approval_date)) }}
          @endif
          </td>
          @elseif( $col == 'apprvrid')
          <td>{{ $otr->mainOT->approver_id }}</td>
          @elseif( $col == 'qrdate')
          <td>
          @if( $otr->mainOT->queried_date == '')
          @else
          {{ date('d-m-Y H:i:s', strtotime($otr->mainOT->queried_date)) }}
          @endif
          </td>
          @elseif( $col == 'qrdby')
          <td>{{ $otr->mainOT->querier_id }}</td>
          @elseif( $col == 'pydate')
          <td>
          @if( $otr->mainOT->payment_date == '')
          @else
          {{ date('d-m-Y', strtotime($otr->mainOT->payment_date)) }}
          @endif
          </td>
          @endif
        @endforeach
        @endif
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
