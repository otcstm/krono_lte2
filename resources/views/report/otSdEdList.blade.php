@extends('adminlte::page')
@section('css')
@stop
@section('title', 'Report')
@section('content')

<h1>Report Details</h1>
<div class="panel panel-default">
<!-- <div class="panel-heading panel-primary">List of Start/End OT Time</div> -->
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
      <th>Date</th>
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
      @elseif( $col == 'dytype')
      <th>Day Type</th>
      @elseif( $col == 'loc')
      <th>Location</th>
      @elseif( $col == 'claim')
      <th>Apply OT Claim?</th>
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
        <td>{{ $otr->URecord->company_id }}</td>
        <td>{{ date('d-m-Y', strtotime($otr->punch_in_time)) }}</td>
        <td>{{ date('H:i:s', strtotime($otr->punch_in_time)) }}</td>
        <td>{{ date('H:i:s', strtotime($otr->punch_out_time)) }}</td>
        @if($cbcolumn ?? '')
        @foreach($cbcolumn as $col)
        @if( $col == 'psarea')
        <td>{{ $otr->URecord->persarea }}</td>
        @elseif( $col == 'psbarea')
        <td>{{ $otr->URecord->perssubarea }}</td>
        @elseif( $col == 'state')
        <td>{{ $otr->URecord->state_id }}</td>
        @elseif( $col == 'region')
        <td>{{ $otr->URecord->Reg->region }}</td>
        @elseif( $col == 'empgrp')
        <td>{{ $otr->URecord->empgroup }}</td>
        @elseif( $col == 'empsubgrp')
        <td>{{ $otr->URecord->empsgroup }}</td>
        @elseif( $col == 'dytype')
        <td>{{ $otr->day_type}}</td>
        @elseif( $col == 'loc')
        <td>({{ $otr->in_latitude }}, {{ $otr->in_longitude }})</td>
        @elseif( $col == 'claim')
        <td>
          {{$otr->ot_applied}}
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
    <form action="{{ route('rep.viewStEd', [], false) }}" method="post">
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
  });
});
</script>
@stop
