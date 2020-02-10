@extends('adminlte::page')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@stop
@section('title', 'Report')
@section('content')

<div class="panel panel-default">
<div class="panel-heading"><strong>Report : Overtime Details</strong></div>
<div class="panel-body">
  <form action="{{ route('otr.viewOTd', [], false) }}" method="post">
  @csrf
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
    <select class="js-example-basic-multiple form-control" name="fcompany[]" multiple="multiple">
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
    <select class="js-example-basic-multiple form-control" name="fstate[]" multiple="multiple">
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
    <select class="js-example-basic-multiple form-control" name="fregion[]" multiple="multiple">
      @if($regions ?? '')
          @foreach($regions as $no=>$region)
    <option value="{{$region->item2}}">{{$region->item3}}</option>
          @endforeach
      @endif
    </select>
  </div>
  </div>

  <div class="col-lg-12">
  <div class="form-group text-center">
    <input type="hidden" name="searching" value="detail">
    <button type="submit" class="btn btn-primary">Search</button>
  </div>
  </div>
  </form>
</div>
</div>

@stop
@section('js')

<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

@stop
