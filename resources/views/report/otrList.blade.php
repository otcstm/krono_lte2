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
      {{-- @elseif( $col == 'salexp')
      <th>Salary Exception</th>--}}
      @elseif( $col == 'capsal')
      <th>Salary Capping for OT</th>
      @elseif( $col == 'empst')
      <th>Employment Status</th>
      @elseif( $col == 'tthour')
      <th>Total Hours</th>
      @elseif( $col == 'ttlmin')
      <th>Total Minutes</th>
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
      @elseif( $col == 'cascomp')
      <th>Charging Company</th>
      @elseif( $col == 'appdate')
      <th>Application Date</th>
      @elseif( $col == 'verdate')
      <th>Verification Date</th>
      @elseif( $col == 'verid')
      <th>Verifier ID</th>
      @elseif( $col == 'vername')
      <th>Verifier Name</th>
      @elseif( $col == 'vercocd')
      <th>Verifier Cocd</th>
      @elseif( $col == 'aprvdate')
      <th>Approval Date</th>
      @elseif( $col == 'apprvrid')
      <th>Approver ID</th>
      @elseif( $col == 'apprvrname')
      <th>Approver Name</th>
      @elseif( $col == 'apprvrcocd')
      <th>Approver Cocd</th>
      @elseif( $col == 'qrdate')
      <th>Queried Date</th>
      @elseif( $col == 'qrdby')
      <th>Queried By</th>
      @elseif( $col == 'pydate')
      <th>Payment Date</th>
      {{--@elseif( $col == 'trnscd')
      <th>Transaction Code</th>--}}
      @elseif( $col == 'dytype')
      <th>Day Type</th>
      @elseif( $col == 'eligday')
      <th>Elig Day</th>
      @elseif( $col == 'eligdaycode')
      <th>Elig Day Code</th>
      @elseif( $col == 'elighm')
      <th>Elig Total Min/Hours</th>
      @elseif( $col == 'elighmcode')
      <th>Elig Total Min/Hours Code</th>
      @elseif( $col == 'emptype')
      <th>Employee Type</th>
      @endif
      @endforeach
      @endif
      </tr>
    </thead>
    <tbody>

      @foreach($otrep as $otr)
      <tr>
        <td>{{ $otr->user_id }}</td>
        <td>{{ $otr->URecord2()->name }}</td>
        <td>{{ $otr->URecord2()->new_ic }}</td>
        <td>{{ $otr->URecord2()->staff_no }}</td>
        <td>{{ $otr->company_id }}</td>
        <td>{{ $otr->refno }}</td>
        <td>{{ date('d-m-Y', strtotime($otr->date)) }}</td>
        @if($cbcolumn ?? '')
        @foreach($cbcolumn as $col)

          @if( $col == 'psarea')
          <td>{{ $otr->persarea ?? 'N/A' }}</td>
          @elseif( $col == 'psbarea')
          <td>{{ $otr->perssubarea ?? 'N/A' }}</td>
          @elseif( $col == 'state')
          <td>{{ $otr->state_id ?? 'N/A' }}</td>
          @elseif( $col == 'region')
          <td>{{ $otr->region ?? 'N/A' }}</td>
          @elseif( $col == 'empgrp')
          <td>{{ $otr->URecord2()->empgroup ?? 'N/A' }}</td>
          @elseif( $col == 'empsubgrp')
          <td>{{ $otr->URecord2()->empsgroup ?? 'N/A' }}</td>
          {{--@elseif( $col == 'salexp')
          <td>
            @if( $otr->sal_exception == 'Y')
            Yes
            @else
            No
            @endif
          </td>--}}
          @elseif( $col == 'capsal')
          <td>
          {{ $otr->salary_exception ?? 'N/A'}}
          </td>
          @elseif( $col == 'empst')
          <td>
            <!-- @if($otr->URecord->empstats == '1')
            Inactive
            @elseif($otr->URecord->empstats == '2')
            {{ $otr->URecord->empstats ?? 'N/A'}}
            @elseif($otr->URecord->empstats == '3')
            Active
            @elseif($otr->URecord->empstats == '0')
            Withdrawn
            @else
            {{ $otr->URecord->empstats ?? 'N/A'}}
            @endif -->
            {{ $otr->URecord2()->empstats ?? 'N/A'}}
          </td>
          @elseif( $col == 'tthour')
          <td>{{ $otr->total_hour }}</td>
          @elseif( $col == 'ttlmin')
          <td>{{ $otr->total_minute }}</td>
          @elseif( $col == 'estamnt')
          <td>{{ $otr->amount }}</td>
          @elseif( $col == 'clmstatus')
          <td>{{ $otr->OTStatus()->item3 ?? $otr->status}}</td>
          @elseif( $col == 'chrtype')
          <td>{{ $otr->charge_type ?? 'N/A'}}</td>
          @elseif( $col == 'bodycc')
          <td>{{ $otr->costcenter ?? 'N/A'}}</td>
          @elseif( $col == 'othrcc')
          <td>{{ $otr->other_costcenter ?? 'N/A'}}</td>
          @elseif( $col == 'prtype')
          <td>{{ $otr->project_type ?? 'N/A'}}</td>
          @elseif( $col == 'pnumbr')
          <td>{{ $otr->project_no ?? 'N/A'}}</td>
          @elseif( $col == 'ntheadr')
          <td>{{ $otr->network_header ?? 'N/A'}}</td>
          @elseif( $col == 'ntact')
          <td>{{ $otr->network_act_no ?? 'N/A'}}</td>
          @elseif( $col == 'ordnum')
          <td>{{ $otr->order_no ?? 'N/A'}}</td>
          @elseif( $col == 'cascomp')
          <td>{{ $otr->company_id ?? 'N/A'}}</td>
          @elseif( $col == 'appdate')
          <td>
            @if( $otr->submitted_date == '')
            N/A
            @else
            {{ date('d-m-Y', strtotime($otr->submitted_date)) }}
            @endif
          </td>
          @elseif( $col == 'verdate')
          <td>
          @if( $otr->verification_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->verification_date)) }}
          @endif
          </td>
          @elseif( $col == 'verid')
          <td>{{ $otr->verifier_id ?? 'N/A'}}</td>

          @elseif( $col == 'vername')
          <td>{{ $otr->verifier->name ?? 'N/A'}}</td>
          @elseif( $col == 'vercocd')
          <td>{{ $otr->verifier->company_id ?? 'N/A'}}</td>

          @elseif( $col == 'aprvdate')
          <td>
          @if( $otr->approved_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->approved_date)) }}
          @endif
          </td>
          @elseif( $col == 'apprvrid')
          <td>{{ $otr->approver_id ?? 'N/A'}}</td>
          @elseif( $col == 'apprvrname')
          <td>{{ $otr->approver->name ?? 'N/A'}}</td>
          @elseif( $col == 'apprvrcocd')
          <td>{{ $otr->approver->company_id ?? 'N/A'}}</td>
          @elseif( $col == 'qrdate')
          <td>
          @if( $otr->queried_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->queried_date)) }}
          @endif
          </td>
          @elseif( $col == 'qrdby')
          <td>{{ $otr->queried_id ?? 'N/A'}}</td>
          @elseif( $col == 'pydate')
          <td>
          @if( $otr->payment_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->payment_date)) }}
          @endif
          </td>
           {{--@elseif( $col == 'trnscd')
          <td>{{ $otr->legacy_code ?? 'N/A'}}</td>--}}
            @elseif( $col == 'dytype')
          {{-- <td>{{ $otr->daytype->description ?? $otr->daytype_id}}</td> --}}
          <td>{{ $otr->DaytypeDesc()->item3 ?? $otr->day_type_code}}</td>

          @elseif( $col == 'eligday')
          <td>{{ $otr->eligible_day ?? 'N/A'}}</td>
          @elseif( $col == 'eligdaycode')
          <td>{{ $otr->eligible_day_code ?? 'N/A'}}</td>
          @elseif( $col == 'elighm')
          <td>{{ $otr->eligible_total_hours_minutes ?? 'N/A'}}</td>
          @elseif( $col == 'elighmcode')
          <td>{{ $otr->eligible_total_hours_minutes_code ?? 'N/A'}}</td>
          @elseif( $col == 'emptype')
          <td>{{ $otr->employee_type ?? 'N/A'}}</td>

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
