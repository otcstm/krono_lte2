@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT Application List @if($show ?? '') {{$claim->date}} ({{date('l', strtotime($claim->date))}})@endif</div>
    <div class="panel-body">
        <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
            @csrf
            <p>Date: <input type="date" id="inputdate" name="inputdate" value="@if($show ?? ''){{$claim->date}}@endif" required></p>
        </form>
        @if($show ?? '')
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
                <button type="button" class="btn btn-primary" id="otedit-0">
                    ADD TIME
                </button>
                <p>Available time to claim: {{$claimtime->hour}}h {{$claimtime->minute}}m</p>
            </div>
            @endif
            @if(session()->has('feedback'))
            <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert" style="display: none">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{session()->get('feedback_text')}}
            </div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="20%">Clock In/Out</th>
                        <th width="20%">Start/End Time</th>
                        <th width="8%">Total Time</th>
                        <th width="40%">Justification</th>
                        @if($claim->status=="Draft")
                        <th width="10%">
                            Action
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                    <tr id="show-{{++$no}}">
                        <td>{{$no }}</td>
                        <td></td>
                        <td>{{ date('H:i', strtotime($singleuser->start_time)) }} - {{ date('H:i', strtotime($singleuser->end_time)) }}</td>
                        <td>{{ $singleuser->hour }}h {{ $singleuser->minute }}m</td>
                        <td>{{ $singleuser->justification }}</td>
                        @if($claim->status=="Draft")
                        <td>
                            <button type="button" class="btn btn-primary" id="otedit-{{$no}}" data-toggle="modal">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delOT" data-ot_id="{{$singleuser->id}}" data-ot_start="{{ date('H:i', strtotime($singleuser->start_time)) }}" data-ot_end="{{ date('H:i', strtotime($singleuser->end_time)) }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
                    <tr id="edit-{{$no}}" style="display: none">
                        <form action="{{route('ot.formadd')}}" method="POST">
                            <td>{{ $no }}</td>
                            @csrf
                            <input type="text" class="form-control hidden" id="edit" name="edit" value="edit" required>
                            <input type="text" class="form-control hidden" id="editid" name="editid" value="{{$singleuser->id}}" required>
                            <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                            <td></td>
                            <td>
                                <input type="time" id="inputstart-{{$no}}" name="inputstart" value="{{ date('H:i', strtotime($singleuser->start_time))}}">
                                <input type="time" id="inputend-{{$no}}" name="inputend" value="{{ date('H:i', strtotime($singleuser->end_time)) }}">
                            </td>
                            <td><span id="inputduration-{{$no}}">{{$singleuser->hour}}h {{$singleuser->minute}}m</span></td>
                            <td>
                                <textarea rows = "3" cols = "60" type="text"  id="inputremark" name="inputremark" placeholder="Write justification" style="resize: none" required>{{$singleuser->justification}}</textarea>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i></button>
                                <button type="button" class="btn btn-danger" id="otx-{{$no}}"><i class="fas fa-times"></i></button>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                    <tr id="edit-0" style="display: none">
                        <form action="{{route('ot.formadd')}}" method="POST">
                            @csrf
                            <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                            <input type="text" class="form-control hidden" id="inputdate" name="inputdate" value="{{$claim->date}}" required>
                            <td>{{count($otlist)+1}}</td>
                            <td>
                                <select name="inputclock" id="inputclock-0" required>
                                    <option hidden disabled selected value="null">Select time</option>
                                    <option value="na">N/A</option>
                                </select>
                            </td>
                            <td>
                                <input type="time" id="inputstart-0" name="inputstart" disabled="true">
                                <input type="time" id="inputend-0" name="inputend" disabled="true">
                            </td>
                            <td><span id="inputduration-0"></span></td>
                            <td>
                                <textarea rows = "3" cols = "60" type="text"  id="inputremark" name="inputremark" value="" placeholder="Write justification" style="resize: none" required></textarea>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i></button>
                                <button type="button" class="btn btn-danger" id="otx-0"><i class="fas fa-times"></i></button>
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
            @if($claim->status=="Draft")
                <form id="formot" action="{{route('ot.formdraft')}}" method="POST">
                    <div class="row">
                        <div class="col-xs-6">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label>Charge Type:</label>
                                    </div>
                                    <div class="col-xs-9">
                                        <select name="chargetype" class="forminput" id="chargetype" required>
                                            <option value="Cost Center">Cost Center</option>
                                            <option value="Project">Project</option>
                                        </select> 
                                    </div>
                                </div>
                                <div id="costcenter">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <label>Charging:</label>
                                        </div>
                                        <div class="col-xs-9">
                                            <select name="charging" class="forminput" id="charging" required>
                                                <option value="ATAC07">ATAC07</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div id="project" style="display:none;">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <label>Type:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="type" class="forminput" id="type" required>
                                                <option value="CUST23234">CUST23234</option>
                                            </select> 
                                        </div>
                                        <div class="col-xs-3">
                                            <label>Header:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="header" class="forminput" id="header" required>
                                                <option value="PRJ123124">PRJ123124</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <label>Code:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="code" class="forminput" id="code" required>
                                                <option value="PRJ123124">PRJ123124</option>
                                            </select> 
                                        </div>
                                        <div class="col-xs-3">
                                            <label>Activity:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="activity" class="forminput" id="activity" required>
                                                <option value="PRJ123124">PRJ123124</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px">
                                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="inputremark">Justification:</label>
                                    </div>
                                    <div class="col-xs-9">
                                        <textarea class="forminput" rows = "5" cols = "60" id="inputremark" name="inputremark" placeholder="Write justification" required>{{$claim->justification}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" style="display: inline"><i class="fas fa-save"></i></button>
                </form>
                <form action="{{route('ot.store')}}" method="POST" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')" style="display: inline">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required style="display: inline">
                    <button type="submit" class="btn btn-primary" style="display: inline">SUBMIT</button>               
                </form>
                </div>
            @endif
        @endif
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
                <form action="{{ route('ot.formdelete') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                    <input type="text" class="form-control hidden" id="delid" name="delid" value="" required>
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
@stop

@section('js')
<script type="text/javascript">
    @if(session()->has('feedback'))
        $("#alert").css("display","block")
    @endif
    //set min and max date
    var dt = new Date();
    var ot = new Date();
    dt.setDate(dt.getDate() - 1);
    ot.setDate(ot.getDate() - 90);
    var m = dt.getMonth()+1;
    var om = ot.getMonth()+1;
    if(m < 10){
        m = "0"+m;
    }
    if(om < 10){
        om = "0"+om;
    }
    d = dt.getDate().toString();
    od = ot.getDate().toString();
    while(d.length<2){
        d = "0"+d;
    }
    while(od.length<2){
        od = "0"+od;
    }

    $("#inputdate").attr("min", ot.getFullYear()+"-"+om+"-"+od);
    $("#inputdate").attr("max", dt.getFullYear()+"-"+m+"-"+d);

    //when date input is changed
    $("#inputdate").change(function(){
    if((Date.parse($("#inputdate").val())-86399000)<=Date.parse(dt)&&(Date.parse($("#inputdate").val()))>=Date.parse(ot)){
            $("#formdate").submit();
        }else{
            alert("Claim date must be between "+ot.getFullYear()+"-"+om+"-"+od+" and "+dt.getFullYear()+"-"+m+"-"+d+"!");
            @if($show ?? '')
                $("#inputdate").val("{{$claim->date}}");
            @else
                $("#inputdate").val("");
            @endif
        }
    });

    //when choose NA
    @if($show ?? '')
    function clock(i){
        return function(){
            if($("#inputclock-"+i).val()=='na'){
                $("#inputstart-"+i).prop('disabled', false);
                $("#inputend-"+i).prop('disabled', false);
                $("#inputstart-"+i).prop('required',true);
                $("#inputend-"+i).prop('required',true);
                $("#inputclock-"+i).prop('disabled', true);
                $("#inputclock-"+i).prop('required',false);
            }else{
                $("#inputstart-"+i).prop('disabled', true);
                $("#inputend-"+i).prop('disabled', true);
                $("#inputstart-"+i).prop('required',false);
                $("#inputend-"+i).prop('required',false);
                $("#inputclock-"+i).prop('disabled', false);
                $("#inputclock-"+i).prop('required',true);
            }
        };
    };

    //check start time & end time
    function checktime(i){
        return function(){
            var st = ($("#inputstart-"+i).val()).split(":");
            var et = ($("#inputend-"+i).val()).split(":");
            var h = parseInt(st[0]);
            var m = parseInt(st[1]);
            var me = "AM";
            if(h>12){
                h = h-12;
                me = "PM"
            }else if(h==0){
                h = 12;
            }
            sh = h.toString();
            while(sh.length<2){
                sh = "0"+sh;
            }
            sm = m.toString();
            while(sm.length<2){
                sm = "0"+sm;
            }
            var start = ((parseInt(st[0]))*60)+(parseInt(st[1]));
            var end = ((parseInt(et[0]))*60)+(parseInt(et[1]));
            if($("#inputstart-"+i).val()!="" && $("#inputend-"+i).val()!=""){
                if(start<end){
                    var total = end-start;
                    var dh = 0;
                    var dm = total;
                    while(total>=60){
                        dh++;
                        total=total-60;
                        dm=total;
                    }
                    $("#inputduration-"+i).text(dh+"h "+dm+"m");
                }else{
                    alert("End time must be more than "+sh+":"+sm+me+"!");
                    $("#inputend-"+i).val("");
                }
            }
        };
    };
    
    function otedit(i){
        return function(){
            $('#edit-'+i).css("display", "table-row");
            $('#show-'+i).css("display", "none");
        };
    };
    
    function otx(i){
        return function(){
            $('#edit-'+i).css("display", "none");
            $('#show-'+i).css("display", "table-row");
        };
    };
    
    for (i=0; i<{{count($otlist)+1}}; i++) {
        $("#inputclock-"+i).change(clock(i));
        $("#inputstart-"+i).change(checktime(i));
        $("#inputend-"+i).change(checktime(i));
        $("#otedit-"+i).on('click',otedit(i));
        $("#otx-"+i).on('click',otx(i));
    };
    @endif

    //when click add time
    $('.otadd').click(function() {
        $('#addNew').css("display", "table-row");
    });

    //when click x in add time
    $('.otx').click(function() {
        $('#addNew').css("display", "none");
    });

    //when delete is clocked
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