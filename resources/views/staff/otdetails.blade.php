@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT Application List {{$claimdate}} ({{$claimday}})</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-6">
                <p>Reference No: {{$claim->refno}}</p>
                <p>State Calendar: </p>
                <span style="color: red"><p>Due Date: {{$claim->date_expiry}}</p>
                <p>Unsubmitted claims will be deleted after the due date</p></span>
            </div>
            <div class="col-xs-6">
                <p>Status: {{$claim->status}}</p>
                <p>Verifier:</p>
                <p>Approver:</p>
            </div>
        </div>
        
        <div class="text-right" style="margin-bottom: 15px">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newOT">
                ADD TIME
            </button>
            <p>Available time to claim: {{$claimtime->hour}}h {{$claimtime->minute}}m</p>
        </div>
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Clock In/Out</th>
                        <th>OT time</th>
                        <th>Total Hour</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $singleuser->refno }}</td>
                        <td>{{ date('H:i', strtotime($singleuser->start_time)) }} - {{ date('H:i', strtotime($singleuser->end_time)) }}</td>
                        <td>{{ $singleuser->hour }}h {{ $singleuser->minute }}m</td>
                        <td>
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
                <h4 class="modal-title">Edit Role</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('ot.addtime')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                    <input type="text" class="form-control hidden" id="inputdate" name="inputdate" value="{{$claimdate}}" required>
                    <div class="form-group">
                        <label for="inputname">Clock In/Out:</label>
                        <!-- <select name="company" id="company" required>
                        {{-- @if($companies ?? '')
                            @foreach($companies as $singlecompany)
                            <option value="{{$singlecompany->id}}">{{$singlecompany->company_descr}}</option>
                            @endforeach
                        @endif --}}
                        </select> -->
                    </div>
                    <!-- <div class="form-group"> -->
                        <label for="inputname">Start/End Time:</label>
                        <input type="time"  id="inputstart" name="inputstart" value="01:00" required>
                        <input type="time" id="inputend" name="inputend" value="02:00" required>
                    <!-- </div> -->
                    <div class="form-group">
                    <label for="inputname">Justification:</label>
                        <input class="form-control" type="text"  id="inputremark" name="inputremark" value="" placeholder="Write justification" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">SAVE</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if(session()->has('feedback'))
<div id="feedback" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-{{session()->get('feedback_icon')}}" style="color: {{session()->get('feedback_color')}}; font-size: 32px;"></div>
                <p>{{session()->get('feedback_text')}}<p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
@endif
@stop

@section('js')
<script type="text/javascript">
// $(document).ready(function() {
//     $('#tOTList').DataTable({
//         "responsive": "true",
//         "order" : [[2, "asc"]],
//     });
// });
@if(session()->has('feedback'))
    $('#feedback').modal('show');   
@endif

$("#inputstart").change(function(){
    var t = ($("#inputstart").val()).split(":");
    var h = parseInt(t[0]);
    var m = parseInt(t[1])+1;
    if(m==60){
        h=h+1;
        m=0;
        if(h==24){
            h=0;
        }
    }
    sh = h.toString();
    while(sh.length<2){
        sh = "0"+sh;

    }
    sm = m.toString();
    while(sm.length<2){
        sm = "0"+sm;
    }
    $("#inputend").attr("min", sh+":"+sm);
});
</script>
@stop