@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<p><a href="{{route('misc.home')}}" style="display: inline">Home</a> > OT List</p>
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT List</div>
    <div class="panel-body">
    
        <div class="text-center" style="margin-bottom: 15px">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newOT">
                CREATE NEW CLAIM
            </button>
        </div>
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reference No</th>
                        <th>Date time</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $singleuser->refno }}</td>
                        <td>{{ $singleuser->date }}</td>
                        <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                        <td>{{ $singleuser->status }}</td>
                        <td>
                            @if($singleuser->status=="Draft")
                                <form action="{{route('ot.edit')}}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="text" class="hidden" id="inputid" name="inputid" value="{{$singleuser->id}}" required>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button>
                                </form>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delOT" data-id="{{$singleuser->id}}" data-date="{{$singleuser->date}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                            <form action="{{route('ot.edit')}}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="text" class="hidden" id="inputid" name="inputid" value="{{$singleuser->id}}" required>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i></button>
                                </form>
                            @endif
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
                    <div class="form-group">
                        <label for="inputname">Select Date:</label>
                        <input type="date" class="form-control" id="inputdate" name="inputdate" value="" required>
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

<div id="delOT" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Claim Time</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete claim for date <span id="deldate"></span>?<p>
                <form action="{{ route('ot.delete') }}" method="POST">
                    @csrf
                    <input type="text" class="hidden" id="delid" name="delid" value="" required>
                    <button type="submit" class="btn btn-primary">DELETE</button>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    });
});

$('#newOT').on('show.bs.modal', function() {
    var dt = new Date();
    var m = dt.getMonth()+1;
    if(m < 10){
        m = "0"+m;
    }
    d = dt.getDate().toString();
    while(d.length<2){
        d = "0"+d;
    }
    $("#inputdate").val(dt.getFullYear()+"-"+m+"-"+d);
    $("#inputdate").attr("max", dt.getFullYear()+"-"+m+"-"+d);
    

    // $("#inputdatestart").val(dt.getFullYear()+"-"+m+"-"+dt.getDate()+"T"+dt.getHours()+":"+dt.getMinutes());
    // $("#inputdateend").val(dt.getFullYear()+"-"+m+"-"+dt.getDate()+"T"+dt.getHours()+":"+dt.getMinutes());
});

$('#delOT').on('show.bs.modal', function(e) {
    var id = $(e.relatedTarget).data('id');
    var date = $(e.relatedTarget).data('date');
    $("#delid").val(id);
    $("#deldate").text(date);
})
</script>
@stop