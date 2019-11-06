@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<p><a href="{{route('misc.home')}}" style="display: inline">Home</a> > <a href="{{route('ot.showOT')}}" style="display: inline">OT List</a> > Apply OT</p>
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT Application List {{$claimdate}} ({{$claimday}})</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-6">
                <p>Reference No: {{$claim->refno}}</p>
                <p>State Calendar: </p>
                @if($claim->status=="Draft")
                    <span style="color: red"><p>Due Date: {{$claim->date_expiry}}</p>
                    <p>Unsubmitted claims will be deleted after the due date</p></span>
                @else
                    <p>Chargin type: {{$claim->charge_type}}
                    <p>Justification: {{$claim->justification}}
                @endif
            </div>
            <div class="col-xs-6">
                <p>Status: {{$claim->status}}</p>
                <p>Verifier:</p>
                <p>Approver:</p>
            </div>
        </div>
        @if($claim->status=="Draft")
            <div class="text-right" style="margin-bottom: 15px">
                <button type="button" class="btn btn-primary otadd" data-toggle="modal" data-target="#newOT" data-ot_id="{{$claim->id}}">
                    ADD TIME
                </button>
                <p>Available time to claim: {{$claimtime->hour}}h {{$claimtime->minute}}m</p>
            </div>
        @endif
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Clock In/Out</th>
                        <th>OT time</th>
                        <th>Total Hour</th>
                        <th>
                            @if($claim->status=="Draft")
                                Action
                            @else
                                Justification
                            @endif
                        </th>
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
                            @if($claim->status=="Draft")
                                <button type="button" class="btn btn-primary otedit" data-toggle="modal" data-target="#newOT" data-ot_id="{{$singleuser->id}}" data-ot_start="{{ date('H:i', strtotime($singleuser->start_time)) }}" data-ot_end="{{ date('H:i', strtotime($singleuser->end_time)) }}" data-ot_remark="{{$singleuser->justification}}">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delOT" data-ot_id="{{$singleuser->id}}" data-ot_start="{{ date('H:i', strtotime($singleuser->start_time)) }}" data-ot_end="{{ date('H:i', strtotime($singleuser->end_time)) }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                                {{ $singleuser->justification }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($claim->status=="Draft")
        <form id="formot" action="{{route('ot.charge')}}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                <label>Charge Type:</label>
                <select name="chargetype" class="forminput" id="chargetype" required>
                    <option value="Cost Center">Cost Center</option>
                    <option value="Project">Project</option>
                </select> 
                <div id="costcenter">
                    <label>Charging:</label>
                    <select name="charging" class="forminput" id="charging" required>
                        <option value="ATAC07">ATAC07</option>
                    </select> 
                </div>
                <div id="project" style="display:none">
                    <label>Type:</label>
                    <select name="type" class="forminput" id="type" required>
                        <option value="CUST23234">CUST23234</option>
                    </select> 
                    <label>Header:</label>
                    <select name="header" class="forminput" id="header" required>
                        <option value="PRJ123124">PRJ123124</option>
                    </select> 
                    <br>
                    <label>Code:</label>
                    <select name="code" class="forminput" id="code" required>
                        <option value="PRJ123124">PRJ123124</option>
                    </select> 
                    <label>Activity:</label>
                    <select name="activity" class="forminput" id="activity" required>
                        <option value="PRJ123124">PRJ123124</option>
                    </select> 
                </div>
            <div>
            <div class="form-group">
                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                <label for="inputremark" style="position:relative; top: -90px">Justification:</label>
                <textarea class="forminput" rows = "5" cols = "50" id="inputremark" name="inputremark" placeholder="Write justification" required>{{$claim->justification}}</textarea>
            <div>
        </form>
        <form action="{{route('ot.store')}}" method="POST" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')">
            @csrf
            <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">SAVE</button>
            </div>
        </form>
        @endif
    </div>
</div>

<div id="newOT" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Time</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('ot.time')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="edit" name="edit" value="null" required>
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                    <input type="text" class="form-control hidden" id="editid" name="editid" value="null" required>
                    <input type="text" class="form-control hidden" id="inputdate" name="inputdate" value="{{$claimdate}}" required>
                    <div class="form-group">
                        <label for="inputclock">Clock In/Out:</label>
                         <select name="inputclock" id="inputclock" required>
                        {{-- @if($companies ?? '')
                            <!--@foreach($companies as $singlecompany)
                            <option value="{{$singlecompany->id}}">{{$singlecompany->company_descr}}</option>
                            @endforeach
                        @endif --}}-->
                        </select> 
                    </div>
                    <label><input type="checkbox" id="ifmanual" name="ifmanual">Manual</label>
                    
                    <div class="form-group">
                        <label>Start/End Time:</label>
                        <input type="time" id="inputstart" name="inputstart" disabled="true">
                        <input type="time" id="inputend" name="inputend" disabled="true">
                    </div>
                    <div class="form-group">
                    <label for="inputremark" style="position:relative; top: -50px">Justification:</label>
                        <textarea rows = "3" cols = "50" type="text"  id="inputremark" name="inputremark" value="" placeholder="Write justification" required></textarea>
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

<div id="delOT" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Claim Time</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete claim for <span id="otstart"></span>-<span id="otend"></span>?<p>
                <form action="{{ route('ot.deltime') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                    <input type="text" class="form-control hidden" id="delid" name="delid" value="" required>
                    <input type="text" class="form-control hidden" id="inputdate" name="inputdate" value="{{$claimdate}}" required>
                    <input type="time" class="form-control hidden" id="otinputstart" name="inputstart" required>
                    <input type="time" class="form-control hidden" id="otinputend" name="inputend" required>
                    <button type="submit" class="btn btn-primary">DELETE</button>
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
    mintime();
});

function mintime(){
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
}

$("#ifmanual").change(function(){
    if($('#ifmanual').is(':checked')) {
        $("#inputstart").prop('disabled', false);
        $("#inputend").prop('disabled', false);
        $("#inputstart").prop('required',true);
        $("#inputend").prop('required',true);
        $("#inputclock").prop('disabled', true);
        $("#inputclock").prop('required',false);
    }else{
        $("#inputstart").prop('disabled', true);
        $("#inputend").prop('disabled', true);
        $("#inputstart").prop('required',false);
        $("#inputend").prop('required',false);
        $("#inputclock").prop('disabled', false);
        $("#inputclock").prop('required',true);
    }
});

$("form .forminput").change(function(){
    if($('#chargetype').val()=="Cost Center") {
        $('#costcenter').css("display", "block");
        $('#project').css("display", "none");
    }else{
        $('#costcenter').css("display", "none");
        $('#project').css("display", "block");
    }
    $("#formot").submit();
});

$("#chargetype").val("{{$claim->charge_type}}");
if($('#chargetype').val()=="Cost Center") {
    $('#costcenter').css("display", "block");
    $('#project').css("display", "none");
}else{
    $('#costcenter').css("display", "none");
    $('#project').css("display", "block");
}

$('.otadd').click(function() {
    $('#edit').val("null");
    $("#editid").val("null");
});

$('.otedit').click(function(e) {
    $('#edit').val("edit");
});

$('#newOT').on('show.bs.modal', function(e) {
    if($('#edit').val()=="edit"){
        var ot_id = $(e.relatedTarget).data('ot_id');
        var ot_start = $(e.relatedTarget).data('ot_start');
        var ot_end = $(e.relatedTarget).data('ot_end');
        var ot_remark = $(e.relatedTarget).data('ot_remark');
        $("#editid").val(ot_id);
        $("#inputstart").val(ot_start);
        $("#inputend").val(ot_end);
        $("#inputremark").val(ot_remark);
        mintime();
    }else{
        $("#inputstart").val("");
        $("#inputend").val("");
        $("#inputremark").val("");
        $("#inputstart").prop('disabled', true);
        $("#inputend").prop('disabled', true);
        $("#inputstart").prop('required',false);
        $("#inputend").prop('required',false);
        $("#inputclock").prop('disabled', false);
        $("#inputclock").prop('required',true);
        $("#ifmanual").prop( "checked", false );
    }
});

$('#delOT').on('show.bs.modal', function(e) {
    var ot_id = $(e.relatedTarget).data('ot_id');
    var ot_start = $(e.relatedTarget).data('ot_start');
    var ot_end = $(e.relatedTarget).data('ot_end');
    $("#delid").val(ot_id);
    $("#otstart").text(ot_start);
    $("#otend").text(ot_end);
    $("#otinputstart").val(ot_start);
    $("#otinputend").val(ot_end);
})

</script>
@stop