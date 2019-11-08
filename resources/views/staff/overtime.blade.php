@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT List</div>
    <div class="panel-body">
    
        <div class="text-center" style="margin-bottom: 15px">
            <form action="{{route('ot.newform')}}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-primary">CREATE NEW CLAIM</button>
            </form>
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
                                <form action="{{route('ot.update')}}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="text" class="hidden" id="inputid" name="inputid" value="{{$singleuser->id}}" required>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button>
                                </form>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delOT" data-id="{{$singleuser->id}}" data-date="{{$singleuser->date}}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                            <form action="{{route('ot.update')}}" method="POST" style="display:inline">
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
                <form action="{{ route('ot.remove') }}" method="POST">
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

$('#delOT').on('show.bs.modal', function(e) {
    var id = $(e.relatedTarget).data('id');
    var date = $(e.relatedTarget).data('date');
    $("#delid").val(id);
    $("#deldate").text(date);
})
</script>
@stop