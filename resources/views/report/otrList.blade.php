@extends('adminlte::page')
@section('css')
@stop
@section('title', 'Report')
@section('content')

<h1>Report Details</h1>
<div class="panel panel-default" id="presult">
<!-- <div class="panel-heading panel-primary">List of OT</div> -->
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
      @elseif( $col == 'dytype')
      <th>Day Type</th>
      @elseif( $col == 'trnscd')
      <th>Transaction Code</th>
      @elseif( $col == 'estamnt')
      <th>Estimated Amount</th>
      @elseif( $col == 'clmstatus')
      <th>Claim Status</th>
      @elseif( $col == 'chrtype')
      <th>Charge Type</th>
      @elseif( $col == 'bodycc')
      <th>Body Cost Center</th>
      @elseif( $col == 'othrcc')
      <th>Other Cost Center</th>
      @elseif( $col == 'prtype')
      <th>Project Type</th>
      @elseif( $col == 'pnumbr')
      <th>Project Number</th>
      @elseif( $col == 'ntheadr')
      <th>Network Header</th>
      @elseif( $col == 'ntact')
      <th>Network Activity</th>
      @elseif( $col == 'ordnum')
      <th>Order Number</th>
      @elseif( $col == 'tthour')
      <th>Total Hours</th>
      @elseif( $col == 'ttlmin')
      <th>Total Minutes</th>
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
        <td>{{ $otr->user_id }}</td>
        <td>{{ $otr->URecord->name }}</td>
        <td>{{ $otr->URecord->new_ic }}</td>
        <td>{{ $otr->URecord->staffno }}</td>
        <td>{{ $otr->company_id }}</td>
        <td>{{ $otr->refno }}</td>
        <td>{{ date('d-m-Y', strtotime($otr->date)) }}</td>
        @if($cbcolumn ?? '')
        @foreach($cbcolumn as $col)
        @if( $col == 'psarea')
        <td>{{ $otr->URecord->persarea }}</td>
        @elseif( $col == 'psbarea')
        <td>{{ $otr->URecord->perssubarea }}</td>
        @elseif( $col == 'state')
        <td>{{ $otr->state_id }}</td>
        @elseif( $col == 'region')
        <td>{{ $otr->region }}</td>
        @elseif( $col == 'empgrp')
        <td>{{ $otr->URecord->empgroup }}</td>
        @elseif( $col == 'empsubgrp')
        <td>{{ $otr->URecord->empsgroup }}</td>
        @elseif( $col == 'salexp')
        <td>
          @if( $otr->sal_exception == 'X')
          Yes
          @else
          No
          @endif
        </td>
        @elseif( $col == 'capsal')
        <td>
          @if( $otr->sal_exception == 'X')
        @else
        {{ $otr->SalCap()->salary_cap }}
        @endif
        </td>
        @elseif( $col == 'empst')
        <td>{{ $otr->URecord->empstats }}</td>
        @elseif( $col == 'dytype')
        <td>{{ $otr->daytype->description }}</td>
        @elseif( $col == 'trnscd')
        <td>{{ $otr->wage_type }}</td>
        @elseif( $col == 'estamnt')
        <td>{{ $otr->amount }}</td>
        @elseif( $col == 'clmstatus')
        <td>{{ $otr->OTStatus()->item3 }}</td>
        @elseif( $col == 'chrtype')
        <td>{{ $otr->charge_type }}</td>
        @elseif( $col == 'bodycc')
        <td>{{ $otr->costcenter }}</td>
        @elseif( $col == 'othrcc')
        <td>{{ $otr->other_costcenter }}</td>
        @elseif( $col == 'prtype')
        <td>{{ $otr->project_type }}</td>
        @elseif( $col == 'pnumbr')
        <td>{{ $otr->project_no }}</td>
        @elseif( $col == 'ntheadr')
        <td>{{ $otr->network_header }}</td>
        @elseif( $col == 'ntact')
        <td>{{ $otr->network_act_no }}</td>
        @elseif( $col == 'ordnum')
        <td>{{ $otr->order_no }}</td>
        @elseif( $col == 'tthour')
        <td>{{ $otr->total_hour }}</td>
        @elseif( $col == 'ttlmin')
        <td>{{ $otr->total_minute }}</td>
        @elseif( $col == 'appdate')
        <td>{{ date('d-m-Y H:i:s', strtotime($otr->created_at)) }}</td>
        @elseif( $col == 'verdate')
        <td>
        @if( $otr->verification_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->verification_date)) }}
        @endif
        </td>
        @elseif( $col == 'verid')
        <td>{{ $otr->verifier_id }}</td>
        @elseif( $col == 'appdate')
        <td>
        @if( $otr->approval_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->approval_date)) }}
        @endif
        </td>
        @elseif( $col == 'apprvrid')
        <td>{{ $otr->approver_id }}</td>
        @elseif( $col == 'qrdate')
        <td>
        @if( $otr->queried_date == '')
        @else
        {{ date('d-m-Y H:i:s', strtotime($otr->queried_date)) }}
        @endif
        </td>
        @elseif( $col == 'qrdby')
        <td>{{ $otr->querier_id }}</td>
        @elseif( $col == 'pydate')
        <td>
        @if( $otr->payment_date == '')
        @else
        {{ date('d-m-Y', strtotime($otr->payment_date)) }}
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
  <div class="form-group text-center">
    <br>
    <form action="{{ route('rep.viewOT', [], false) }}" method="post">
        @csrf
        <button type="submit" name="return" value="rtn" class="btn btn-primary">RETURN</button>
    </form>
  </div>
</div>
</div>
@stop
@section('js')

<script type="text/javascript">
$(document).ready(function() {
  $('#tOtlist').DataTable({
    "responsive": "true",
    "order" : [[0, "asc"]],
    dom: '<"flext"lB>rtip',
    buttons: [
         'excel'
    ]
    // dom: 'Bfrtip',
		// buttons: [
    //   {extend: 'excelHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Summary Report', sheetName: 'OT Summary', title: 'OT Summary Report'},
  	// 	{extend: 'colvis', collectionLayout: 'fixed three-column'}]
  });
});
</script>
@stop
