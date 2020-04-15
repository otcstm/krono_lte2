@extends('adminlte::page')
@section('title', 'Report')
@section('content')

<h1>Overtime Log Changes Report</h1>
<div class="panel panel-default panel-main">
<div class="panel panel-default">
<div class="panel-heading"><strong>Select Report Parameter</strong></div>
<div class="panel-body">
  <form action="{{ route('rep.sa.OTLog', [], false) }}" method="GET">

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
        <button type="submit" name="searching" value="gexcelLC" class="btn btn-primary">GENERATE REPORT</button>
      </div>
    </div>
  </form>
  </div>
  </div>


<div class="panel panel-default" id="lofhis">
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
    $('#repothist').DataTable({
        "responsive": "true",
        "order" : [[0, "desc"]],

    });
});
</script>
@stop
