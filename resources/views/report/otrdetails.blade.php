@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
@stop
@section('title', 'Report')
@section('content')
<div class="panel panel-default">
<div class="panel-heading"><strong>Report : Overtime Details</strong></div>
<div class="panel-body">
  <form action="{{ route('otr.viewOTd', [], false) }}" method="post">
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
  </div>    <div class="col-sm-6">
  <div class="form-group">
  	<label for="fapprover_id">Approver ID</label>
  	<input type="text" class="form-control" id="fapprover_id" name="fapprover_id">
  </div>
  </div>
  <div class="col-sm-6">
  <div class="form-group">
  	<label for="fverifier_id">Verifier ID</label>
  	<input type="text" class="form-control" id="fverifier_id" name="fverifier_id">
  </div>
  </div>
  <div class="col-sm-12">
  <div class="form-group text-center">
    <input type="hidden" name="searching" value="detail">
    <button type="submit" class="btn btn-primary">Search</button>
  </div>
  </div>
  </form>
</div>
</div>

@if($vlist == true)
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
      <th>Estimated Amount</th>
      <th>Claim Status</th>
      <th>Charge Type</th>
      <th>Number of Hours</th>
      <th>Number of Minutes</th>
      <th>Justification</th>
      <th>Application Date</th>
      <th>Verification Date</th>
      <th>Verified By</th>
      <th>Approval Date</th>
      <th>Approved By</th>
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
      <td>{{ $otr->start_time }}</td>
      <td>{{ $otr->end_time }}</td>
      <td>{{ $otr->is_manual }}</td>
      <td>{{ $otr->mainOT->daytype_id }}</td>
      <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
      <td>{{ $otr->mainOT->wage_type }}</td>
      <td>{{ $otr->amount }}</td>
      <td>
        @if( $otr->checked == 'Y')
        {{ $otr->mainOT->OTStatus()->item3 }}
        @else
        Draft
        @endif
      </td>
      <td>{{ $otr->mainOT->charge_type }}</td>
      <td>{{ $otr->hour }}</td>
      <td>{{ $otr->minute }}</td>
      <td>{{ $otr->justification }}</td>
      <td>{{ date('d-m-Y H:i:s', strtotime($otr->mainOT->created_at)) }}</td>
      <td>{{ date('d-m-Y H:i:s', strtotime($otr->mainOT->verification_date)) }}</td>
      <td>{{ $otr->mainOT->verifier_id }}</td>
      <td>{{ date('d-m-Y H:i:s', strtotime($otr->mainOT->approval_date)) }}</td>
      <td>{{ $otr->mainOT->approver_id }}</td>
      <td>{{ date('d-m-Y H:i:s', strtotime($otr->mainOT->queried_date)) }}</td>
      <td>{{ $otr->mainOT->querier_id }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->mainOT->payment_date)) }}</td>
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
    {extend: 'excelHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
    {extend: 'pdfHtml5', exportOptions: {columns: ':visible'}, filename: 'OT Details Report', sheetName: 'OT Details', title: 'OT Details Report'},
		{extend: 'colvis', collectionLayout: 'fixed three-column'}]
  });
});


@if(session()->has('feedback'))
    $('#feedback').modal('show');
@endif
</script>
@stop
