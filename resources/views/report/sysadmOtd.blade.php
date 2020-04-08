@extends('adminlte::page')
@section('title', 'Report')
@section('content')

<div class="panel panel-default">
<div class="panel-heading"><strong>Report : Overtime Details</strong></div>
<div class="panel-body">
  <form action="{{ route('rep.sa.OTd', [], false) }}" method="GET">

  <div class="col-lg-6">
  <div class="form-group">
    	<label for="fdate">From</label>
  	<input type="date" class="form-control" id="fdate" name="fdate" required autofocus>
  </div>
  </div>
  <div class="col-lg-6">
  <div class="form-group">
  	<label for="tdate">To</label>
  	<input type="date" class="form-control" id="tdate" name="tdate"  required autofocus>
  </div>
  </div>

  <div class="col-lg-6">
  <div class="form-group">
  	<label for="frefno">Refno</label>
  	<input type="text" class="form-control" id="frefno" name="frefno">
  </div>
  </div>
  <div class="col-lg-6">
  <div class="form-group">
  	<label for="fapprover_id">Approver ID</label>
  	<input type="text" class="form-control" id="fapprover_id" name="fapprover_id">
  </div>
  </div>
  <div class="col-lg-6">
  <div class="form-group">
  	<label for="fverifier_id">Verifier ID</label>
  	<input type="text" class="form-control" id="fverifier_id" name="fverifier_id">
  </div>
  </div>

  <div class="col-lg-6">
  <div class="form-group">
    <label for="fpersno">Persno </label>
    <input type="text" class="form-control" id="fpersno" name="fpersno" placeholder="Use commas to search multiple persno, e.g. 30013,45450,38884">
  </div>
  </div>
  <div class="col-lg-12">
  <div class="form-group">
    <label for="fcompany">Company Code</label>
    <br>
    <select class="selectReport form-control" name="fcompany[]" multiple="multiple">
      @if($companies ?? '')
          @foreach($companies as $no=>$company)
    <option value="{{$company->id}}">{{$company->id}}-{{$company->company_descr}}</option>
          @endforeach
      @endif
    </select>
  </div>
  </div>
  <div class="col-lg-12">
  <div class="form-group">
    <label for="fstate">State</label>
    <br>
    <select class="selectReport form-control" name="fstate[]" multiple="multiple">
      @if($states ?? '')
          @foreach($states as $no=>$state)
    <option value="{{$state->id}}">{{$state->id}}-{{$state->state_descr}}</option>
          @endforeach
      @endif
    </select>
 </div>
  </div>
  <div class="col-lg-12">
  <div class="form-group">
    <label for="fregion">Region</label>
    <br>
    <select class="selectReport form-control" name="fregion[]" multiple="multiple">
      @if($regions ?? '')
          @foreach($regions as $no=>$region)
    <option value="{{$region->item2}}">{{$region->item3}}</option>
          @endforeach
      @endif
    </select>
  </div>
  </div>

  <div class="col-sm-3">
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="psarea" id="persarea" name="cbcol[]" >
    <label class="form-check-label" for="persarea"> Personnel Area  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="psbarea" id="persbarea" name="cbcol[]" >
    <label class="form-check-label" for="persbarea"> Personnel Subarea  </label>
  </div>
  <div class="form-check">
    <input class="form-check-input-inline" type="checkbox" value="state" id="st" name="cbcol[]" >
    <label class="form-check-label" for="st"> State  </label>
  </div>
  </div>

  <div class="col-lg-12">  <BR>
  <div class="form-group text-center">
    <button type="submit" name="searching" value="gexceld" class="btn btn-primary">Generate Report</button>
  </div>
  </div>
  </form>
</div>
</div>

<div class="panel panel-default" id="psearch">
<div class="panel-heading"><strong>Reports History</strong></div>
<div class="panel-body">
  <div class="table-responsive">
  <table id="repothist" class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th scope="col">Submit Date</th>
        <th scope="col">Group</th>
        <th scope="col">Status</th>
        <th scope="col">Data From</th>
        <th scope="col">Data To</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($history as $his)
      <tr>
        <td>{{ $his->created_at }}</td>
        <td>{{ $his->job_type }}</td>
        <td>{{ $his->status }}</td>
        <td>{{ $his->from_date }}</td>
        <td>{{ $his->to_date }}</td>
        @if($his->status == 'Completed')
        <td><form action="{{ route('rep.sa.dOT', [], false)}}"
          method="post">
          <input type="hidden" name="bjid" value="{{ $his->id }}" />
          @csrf
          <button type="submit" class="btn btn-sm btn-info" title="Download"><i class="fa fa-download"></i></button>
        </form></td>
        @else
        <td>...</td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
</div>

</div>


@stop
@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('.selectReport').select2({
      closeOnSelect: false
    });
});
</script>
<script>
$(function() {
$('#multiselect').multiselect();});
</script>
@stop
