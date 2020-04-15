@extends('adminlte::page')
@section('css')
@stop
@section('title', 'Report')
@section('content')

<h1>Report Details</h1>
<div class="panel panel-default">
<!-- <div class="panel-heading panel-primary">List of Log Changes</div> -->
<div class="panel-body">
  <div class="table-responsive">
  <table id="tOtlist" class="table table-bordered">
    <thead>
      <tr>
      <th>Reference Number</th>
      <th>Personnel Number</th>
      <th>Employee Name</th>
      <th>IC Number</th>
      <th>Staff ID</th>
      <th>Action Date</th>
      <th>Action Time</th>
      <th>Action By</th>
      <th>Action Log</th>
      <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
      @foreach($otrep as $otr)
      <tr>
      <td>{{ $otr->detail->refno }}</td>
      <td>{{ $otr->detail->user_id }}</td>
      <td>{{ $otr->detail->URecord->name }}</td>
      <td>{{ $otr->detail->URecord->new_ic }}</td>
      <td>{{ $otr->detail->URecord->staffno }}</td>
      <td>{{ date('d-m-Y', strtotime($otr->created_at)) }}</td>
      <td>{{ date('H:i:s', strtotime($otr->created_at)) }}</td>
      <td>{{ $otr->user_id }}</td>
      <td>{{ $otr->action }}</td>
      <td>
        @if( $otr->action == 'Submitted')
          Submitted with justification : <br>
          <?php $i=1;?>
          @foreach($otdetail as $otd)
            @if( $otr->ot_id == $otd->ot_id && $otd->justification != '')
            <?php echo $i;?>
          .{{$otd->justification }}<br>
            <?php $i++;?>
            @endif
          @endforeach
        @else
        {{ $otr->message }}
        @endif
      </td>
      </tr>
      @endforeach

    </tbody>
  </table>
  </div>
  <div class="form-group text-center">
    <br>
    <form action="{{ route('rep.viewOTLog', [], false) }}" method="post">
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
        'csv', 'excel', 'pdf'
    ]
  });
});
</script>
@stop
