@extends('adminlte::page')
@section('title', 'Report')
@section('content')

<h1>Overtime Log Changes Report</h1>
<div class="panel panel-default panel-main">
<div class="panel panel-default">
<div class="panel-heading"><strong>Select Report Parameter</strong></div>
<div class="panel-body">
  <form action="{{ route('rep.viewOTLog', [], false) }}" method="post">
  @csrf
  <div class="row">
    <div class="col-md-6">
      <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="fpersno">Persno</label>
        </div>
        <div class="col-md-9">
          <input type="text" class="form-control" id="fpersno" name="fpersno" required placeholder="Use commas to search multiple persno, e.g. 30013,45450,38884">
        </div>
      </div>
      <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
          <label for="frefno">Refno</label>
        </div>
        <div class="col-md-9">
          <input type="text" class="form-control" id="frefno" name="frefno">
        </div>
      </div>
      <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
      	   <label for="fdate">Overtime Date</label>
        </div>
        <div class="col-md-4">
          <input type="date" class="form-control" id="fdate" name="fdate" required autofocus>
        </div>
        <div class="col-md-1">
           <label for="fdate">To</label>
        </div>
        <div class="col-md-4">
          <input type="date" class="form-control"  id="tdate" name="tdate"  required autofocus>
        </div>
      </div>
    </div>
  </div>
  </div>

  <div class="panel-footer">
    <div class="text-right">
      <button type="submit" name="searching" value="excellog" class="btn btn-primary btn-outline">DOWNLOAD REPORT</button>
      <button type="submit" name="searching" value="log" class="btn btn-primary">DISPLAY REPORT</button>
    </div>
  </div>
</form>
</div>
</div>
</div>
@stop
