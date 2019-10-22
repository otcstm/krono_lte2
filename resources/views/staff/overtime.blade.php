@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT List</div>
    <div class="panel-body">
    
        <div class="text-center" style="margin-bottom: 15px">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newOT">
                CREATE NEW OT
            </button>
            <p>Eligible time remaining: {{$hour}}h {{$minute ?? ''}}m<p>
        </div>
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reference No</th>
                        <th>Date time</th>
                        <th>Day/Hour</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overtimes as $no=>$singleuser)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $singleuser->refno }}</td>
                        <td>{{ $singleuser->title }}</td>
                        <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                        <td>{{ $singleuser->status }}</td>
                        <td>
                        <input type="datetime-local">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRole">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRole">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="newOT" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New OT Claim</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('ot.create')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="hour" name="hour" value="{{$hour}}" required>
                    <input type="text" class="form-control hidden" id="minute" name="minute" value="{{$minute}}" required>
                    <div class="form-group">
                        <label for="inputname">Select Date:</label>
                        <input type="date" class="form-control" id="inputdate" name="inputdate" value="" required>
                        <!-- <input type="datetime-local" id="inputdatestart" name="inputdatestart" value="">
                        <input type="datetime-local" id="inputdateend" name="inputdateend" value="3414-01-22T03:02" min="2018-06-07T00:00" max="2018-06-14T00:00"> -->
                    </div>
                    <div class="form-group">
                        <label for="inputname">Start Time:</label>
                        <input type="time" class="form-control" id="inputtimestart" name="inputtimestart" value="" required>
                    </div>    
                    <div class="form-group">
                        <label for="inputname">End Time:</label>
                        <input type="time" class="form-control" id="inputtimeend" name="inputtimeend" value="" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">CREATE</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tOTList').DataTable({
        "responsive": "true",
        "order" : [[2, "asc"]],
    });
});

$('#newOT').on('show.bs.modal', function() {
    var dt = new Date();
    var m = dt.getMonth();
    if(m < 10){
        m = "0"+m;
    }
    $("#inputdate").val(dt.getFullYear()+"-"+m+"-"+dt.getDate());
    $("#inputtimestart").val(dt.getHours()+":"+dt.getMinutes());
    $("#inputtimeend").val(dt.getHours()+":"+dt.getMinutes());

    // $("#inputdatestart").val(dt.getFullYear()+"-"+m+"-"+dt.getDate()+"T"+dt.getHours()+":"+dt.getMinutes());
    // $("#inputdateend").val(dt.getFullYear()+"-"+m+"-"+dt.getDate()+"T"+dt.getHours()+":"+dt.getMinutes());
    
});
</script>
@stop