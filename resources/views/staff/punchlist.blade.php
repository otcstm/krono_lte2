@extends('adminlte::page')

@section('title', 'Punch Dashboard')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">Punch {{ $punch_status }}</div>
  <div class="panel-body text-center">
    @if ($errors->has('punch'))
    <div class="alert alert-warning alert-dismissible">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>{{ $errors->first('punch') }}</strong>
    </div>
    @endif
    <form action="{{ $p_url }}" method="post">
      @csrf
      <button type="submit" class="btn btn-{{ $btncol }}">Punch {{ $punch_status }}</button>
    </form>
  </div>
</div>
@if($p_gotdata == true)
<div class="panel panel-default">
  <div class="panel-heading">Punch History</div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="tPunchHIstory" class="table table-hover table-bordered">
       <thead>
         <tr>
           <th>Clock-in</th>
           <th>Clock-out</th>
           <th>Status</th>
           <th>Action</th>
         </tr>
       </thead>
       <tbody>
         @foreach($p_list as $ap)
         <tr>
           <td>{{ $ap->punch_in_time }}</td>
           <td>{{ $ap->punch_out_time }}</td>
           <td>{{ $ap->status }}</td>
           <td><button type="button" class="btn btn-sm btn-primary">Aksi</button></td>
         </tr>
         @endforeach
       </tbody>
     </table>
    </div>
  </div>
</div>
@endif
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tPunchHIstory').DataTable({
      "responsive": "true",
      "order" : [[0, "desc"]],
      fixedHeader : true
    });
} );
</script>
@stop
