@extends('adminlte::page')
@section('title', 'Report')
@section('content')
<div class="panel panel-default">
<div class="panel-heading"><strong>Report : Overtime Log Changes</strong></div>
<div class="panel-body">
  <form action="{{ route('otr.viewOTLog', [], false) }}" method="post">
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
  	<input type="text" class="form-control" id="fpersno" name="fpersno" placeholder="Use commas to search multiple persno, e.g. 30013,45450,38884">
    </div>
  </div>
  <div class="col-sm-6">
  <div class="form-group">
  	<label for="frefno">Refno</label>
  	<input type="text" class="form-control" id="frefno" name="frefno">
  </div>
  </div>
  <div class="col-sm-12">
  <div class="form-group text-center">
    <input type="hidden" name="searching" value="log">
    <button type="submit" class="btn btn-primary">Search</button>
  </div>
  </div>
  </form>
</div>
</div>
@stop
