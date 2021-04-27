@extends('adminlte::page')
@section('css')
@stop
@section('title', 'Report')
@section('content')

<h1>Report Details</h1>
<div class="panel panel-default">
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
      {{--@elseif( $col == 'salexp')
      <th>Salary Exception</th>--}}
      @elseif( $col == 'capsal')
      <th>Salary Capping for OT</th>
      @elseif( $col == 'empst')
      <th>Employment Status</th>
      @elseif( $col == 'st')
      <th>Start Time</th>
      @elseif( $col == 'et')
      <th>End Time</th>
      @elseif( $col == 'mflag')
      <th>Manual Flag</th>
      @elseif( $col == 'loc')
      <th>Location</th>
      @elseif( $col == 'noh')
      <th>Number of Hours</th>
      @elseif( $col == 'nom')
      <th>Number of Minutes</th>
      @elseif( $col == 'jst')
      <th>Justification</th>
      {{--@elseif( $col == 'estamnt')
      <th>Estimated Amount(RM)</th>--}}
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
      <th>Transaction Code</th>
      @elseif( $col == 'dytype')
      <th>Day Type</th>
      --}}
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
        <td>{{ $otr->mainOT->user_id }}</td>
        <td>{{ $otr->mainOT->URecord2()->name }}</td>
        <td>{{ $otr->mainOT->URecord2()->new_ic }}</td>
        <td>{{ $otr->mainOT->URecord2()->staffno }}</td>
        <td>{{ $otr->mainOT->company_id }}</td>
        <td>{{ $otr->mainOT->refno }}</td>
        <td>{{ date('d-m-Y', strtotime($otr->mainOT->date)) }}</td>

        @if($cbcolumn ?? '')
        @foreach($cbcolumn as $col)
          @if( $col == 'psarea')
          <td>{{ $otr->mainOT->persarea ?? 'N/A' }}</td>
          @elseif( $col == 'psbarea')
          <td>{{ $otr->mainOT->perssubarea ?? 'N/A' }}</td>
          @elseif( $col == 'state')
          <td>{{ $otr->mainOT->state_id ?? 'N/A'}}</td>
          @elseif( $col == 'region')
          <td>{{ $otr->mainOT->region ?? 'N/A'}}</td>
          @elseif( $col == 'empgrp')
          <td>{{ $otr->mainOT->URecord2()->empgroup ?? 'N/A'}}</td>
          @elseif( $col == 'empsubgrp')
          <td>{{ $otr->mainOT->URecord2()->empsgroup ?? 'N/A'}}</td>
          {{--  @elseif( $col == 'salexp')
          <td>
          @if( $otr->mainOT->sal_exception == 'Y')
          Yes
          @else
          No
          @endif
          </td>--}}
          @elseif( $col == 'capsal')
          <td>
          {{ $otr->mainOT->salary_exception ?? 'N/A'}}

          {{--@if( $otr->mainOT->salary_exception == 'Y')
          N/A
          @else
          {{ $otr->mainOT->SalCap()->salary_cap ?? 'Overtime Eligibility Error' }}
          @endif--}}

          </td>
          @elseif( $col == 'empst')
          <td>
            <!-- @if($otr->mainOT->URecord->empstat == '1')
            Inactive
            @elseif($otr->mainOT->URecord->empstat == '2')
            {{$otr->mainOT->URecord->empstat ?? 'N/A'}}
            @elseif($otr->mainOT->URecord->empstat == '3')
            Active
            @elseif($otr->mainOT->URecord->empstat == '0')
            Withdrawn
            @else
            {{ $otr->mainOT->URecord->empstat ?? 'N/A'}}
            @endif -->
            {{ $otr->mainOT->URecord2()->empstats ?? 'N/A'}}

          </td>
          @elseif( $col == 'st')
          <td>{{ date('H:i:s', strtotime($otr->start_time)) }}</td>
          @elseif( $col == 'et')
          <td>{{ date('H:i:s', strtotime($otr->end_time)) }}</td>
          @elseif( $col == 'mflag')
          <td>{{ $otr->is_manual }}</td>
          @elseif( $col == 'loc')
          <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
          @elseif( $col == 'noh')
          <td>{{ $otr->hour }}</td>
          @elseif( $col == 'nom')
          <td>{{ $otr->minute }}</td>
          @elseif( $col == 'jst')
          <td>{{ $otr->justification ?? 'N/A'}}</td>
          {{--@elseif( $col == 'estamnt')
          <td>{{ $otr->amount ?? 'N/A'}}</td>--}}
          @elseif( $col == 'clmstatus')
          <td>{{ $otr->mainOT->OTStatus()->item3 ?? $otr->mainOT->status }}</td>
          @elseif( $col == 'chrtype')
          <td>{{ $otr->mainOT->charge_type ?? 'N/A'}}</td>
          @elseif( $col == 'bodycc')
          <td>{{ $otr->mainOT->costcenter?? 'N/A' }}</td>
          @elseif( $col == 'othrcc')
          <td>{{ $otr->mainOT->other_costcenter ?? 'N/A'}}</td>
          @elseif( $col == 'prtype')
          <td>{{ $otr->mainOT->project_type ?? 'N/A' }}</td>
          @elseif( $col == 'pnumbr')
          <td>{{ $otr->mainOT->project_no ?? 'N/A'}}</td>
          @elseif( $col == 'ntheadr')
          <td>{{ $otr->mainOT->network_header ?? 'N/A'}}</td>
          @elseif( $col == 'ntact')
          <td>{{ $otr->mainOT->network_act_no ?? 'N/A'}}</td>
          @elseif( $col == 'ordnum')
          <td>{{ $otr->mainOT->order_no ?? 'N/A'}}</td>
          @elseif( $col == 'cascomp')
          <td>{{ $otr->mainOT->company_id ?? 'N/A'}}</td>
          @elseif( $col == 'appdate')
          <td>
          @if( $otr->mainOT->submitted_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->mainOT->submitted_date)) }}
          @endif
          </td>
          @elseif( $col == 'verdate')
          <td>
          @if( $otr->mainOT->verification_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->mainOT->verification_date))}}
          @endif
          </td>
          @elseif( $col == 'verid')
          <td>{{ $otr->mainOT->verifier_id ?? 'N/A'}}</td>
          @elseif( $col == 'vername')
          <td>{{ $otr->mainOT->verifier->name ?? 'N/A'}}</td>
          @elseif( $col == 'vercocd')
          <td>{{ $otr->mainOT->verifier->company_id ?? 'N/A'}}</td>

          @elseif( $col == 'aprvdate')
          <td>
            @if( $otr->mainOT->approved_date == '')
            N/A
            @else
            {{ date('d-m-Y', strtotime($otr->mainOT->approved_date))}}
            @endif
          </td>
          @elseif( $col == 'apprvrid')
          <td>{{ $otr->mainOT->approver_id ?? 'N/A'}}</td>
          @elseif( $col == 'apprvrname')
          <td>{{ $otr->mainOT->approver->name ?? 'N/A'}}</td>
          @elseif( $col == 'apprvrcocd')
          <td>{{ $otr->mainOT->approver->company_id ?? 'N/A'}}</td>

          @elseif( $col == 'qrdate')
          <td>
          @if( $otr->mainOT->queried_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->mainOT->queried_date)) }}
          @endif
          </td>
          @elseif( $col == 'qrdby')
          <td>{{ $otr->mainOT->querier_id ?? 'N/A'}}</td>
          @elseif( $col == 'pydate')
          <td>
          @if( $otr->mainOT->payment_date == '')
          N/A
          @else
          {{ date('d-m-Y', strtotime($otr->mainOT->payment_date)) }}
          @endif
          </td>
          {{--
          @elseif( $col == 'trnscd')
          <td>{{ $otr->mainOT->legacy_code ?? 'N/A'}}</td>
          @elseif( $col == 'dytype')
          <!-- <td>{{ $otr->mainOT->daytype->description ?? $otr->mainOT->daytype_id}}</td> -->
          <td>{{ $otr->mainOT->daytype->code ?? $otr->mainOT->daytype_id}}</td>
          --}}
          @elseif( $col == 'emptype')
          <td>{{ $otr->mainOT->employee_type ?? 'N/A'}}</td>
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
    <form action="{{ route('rep.viewOTd', [], false) }}" method="post">
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
    // {extend: 'excelHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
    // {extend: 'pdfHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
		// {extend: 'colvis', collectionLayout: 'fixed three-column'}]
  });
});
</script>
@stop
