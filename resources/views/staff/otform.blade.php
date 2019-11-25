@extends('adminlte::page')

@section('title', 'Overtime Form')

@section('content')
<style>
    table.table-bordered{
        border:1px solid #A9A9A9;
    }
    table.table-bordered > thead > tr > th{
        border:1px solid #A9A9A9;
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid #A9A9A9;
    }
</style>

<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT Application List @if($show ?? '') {{$claim->date}} ({{date('l', strtotime($claim->date))}})@endif</div>
    <div class="panel-body">
        @if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif
        <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
            @csrf
            <p>Date: <input type="date" id="inputdate" name="inputdate" value="@if($show ?? ''){{$claim->date}}@endif" required></p>
        </form>
        @if($show ?? '')
            <div class="row">
                <div class="col-xs-6">
                    <p>Reference No: {{$claim->refno}}</p>
                    <p>State Calendar: </p>
                    @if(($claim->status=="Draft (Incomplete)")||($claim->status=="Draft (Complete)"))
                        <span style="color: red"><p>Due Date: {{$claim->date_expiry}}</p>
                        <p>Unsubmitted claims will be deleted after the due date</p></span>
                    @else
                        <p>Charging type: {{$claim->charge_type}}
                        <p>Justification: {{$claim->justification}}
                    @endif
                </div>
                <div class="col-xs-6">
                    <p>Status: {{$claim->status}}</p>
                    <p>Verifier: {{$claim->verifier->name}}</p>
                    <p>Approver: {{$claim->approver->name}}</p>
                </div>
            </div>
            <div class="row" style="display: flex;;">
                <div class="col-xs-6" style="display: flex; align-items: flex-end;">
                    <p><b>TIME LIST</b></p>
                </div>
                <div class="col-xs-6">
                    @if(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query")))
                    <div class="text-right" >
                        <button type="button" class="btn btn-primary" id="otedit-0">
                            ADD TIME
                        </button>
                        <p>Available time to claim: {{$claimtime->hour}}h {{$claimtime->minute}}m</p>
                    </div>
                    @endif
                </div>
            </div>
            <table class="table table-bordered">
                <thead>    
                    <tr class="info">
                        <th width="2%">No</th>
                        <th width="20%">Clock In/Out</th>
                        <th width="20%">Start/End Time</th>
                        <th width="8%">Total Time</th>
                        <th width="40%">Justification</th>
                        @if(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query")))
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
                        @if(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query")))
                        <td>
                            <button type="button" class="btn btn-primary" id="otedit-{{$no}}" data-toggle="modal">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form action="{{ route('ot.formdelete') }}" onsubmit="return confirm('Are you sure you want to delete claim for {{ date('H:i', strtotime($singleuser->start_time)) }}-{{ date('H:i', strtotime($singleuser->end_time)) }}?');" method="POST" style="display: inline">
                                @csrf
                                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                                <input type="text" class="form-control hidden" id="delid" name="delid" value="{{$singleuser->id}}" required>
                                <input type="time" class="form-control hidden" id="otinputstart" name="inputstart" value="{{ date('H:i', strtotime($singleuser->start_time)) }}" required>
                                <input type="time" class="form-control hidden" id="otinputend" name="inputend" value="{{ date('H:i', strtotime($singleuser->end_time)) }}" required>
                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
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
                    @if(count($otlist)==0)
                        <tr id="nodata" class="text-center"><td colspan="6"><i>Not Available</i></td></tr>
                    @endif
                    <tr id="edit-0" style="display: none">
                        <form action="{{route('ot.formadd')}}" method="POST">
                            @csrf
                            <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                            <input type="text" class="form-control hidden" id="inputdate" name="inputdate" value="{{$claim->date}}" required>
                            <input type="text" class="form-control hidden" id="claimcharge" name="claimcharge" value="{{$claim->charge_type}}">
                            <input type="text" class="form-control hidden" id="claimremark" name="claimremark" value="{{$claim->justification}}">
                            <td>{{count($otlist)+1}}</td>
                            <td>
                                <select name="inputclock" id="inputclock-0" required>
                                    <option hidden disabled selected value="">Select Time</option>
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
            @if(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query")))
                <form id="formot" action="{{route('ot.save')}}" method="POST">
                    <div class="row">
                        <div class="col-xs-6">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                                <input type="text" class="form-control hidden" id="save" name="save" value="submit" required>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label>Charge Type:</label>
                                    </div>
                                    <div class="col-xs-9">
                                        <select name="chargetype" class="forminput inputcheck-{{$i=0}}" id="chargetype" value="{{$claim->charge_type}}" required>
                                            <option hidden disabled value="" @if($claim->charge_type=="") selected @endif>Select Charge Type</option>
                                            <option value="Cost Center" @if($claim->charge_type=="Cost Center") selected @endif>Cost Center</option>
                                            <option value="Project" @if($claim->charge_type=="Project") selected @endif>Project</option>
                                        </select> 
                                    </div>
                                </div>
                                <div id="costcenter" @if($claim->charge_type!="Cost Center") style="display: none" @endif>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <label>Charging:</label>
                                        </div>
                                        <div class="col-xs-9">
                                            <select name="charging" id="charging" class="forminput @if($claim->charge_type=="Cost Center")inputcheck-{{++$i}}" required @else " @endif>
                                                <option value="ATAC07">ATAC07</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div id="project"  @if($claim->charge_type!="Project") style="display: none" @endif>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <label>Type:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="type" id="type" class="forminput @if($claim->charge_type=="Project")inputcheck-{{++$i}}" required @else " @endif>
                                                <option value="CUST23234">CUST23234</option>
                                            </select> 
                                        </div>
                                        <div class="col-xs-3">
                                            <label>Header:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="header" id="header" class="forminput @if($claim->charge_type=="Project")inputcheck-{{++$i}}" required @else " @endif>
                                                <option value="PRJ123124">PRJ123124</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <label>Code:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="code" id="code" class="forminput @if($claim->charge_type=="Project")inputcheck-{{++$i}}" required @else " @endif>
                                                <option value="PRJ123124">PRJ123124</option>
                                            </select> 
                                        </div>
                                        <div class="col-xs-3">
                                            <label>Activity:</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <select name="activity" id="activity" class="forminput @if($claim->charge_type=="Project")inputcheck-{{++$i}}" required @else " @endif>
                                                <option value="PRJ123124">PRJ123124</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: -10px">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="inputremark">Justification:</label>
                                    </div>
                                    <div class="col-xs-9">
                                        <textarea class="forminput inputcheck-{{++$i}}" rows = "5" cols = "60" id="inputremark" name="inputremark" placeholder="Write justification" required>{{$claim->justification}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{route('ot.list')}}"><button type="button" class="btn btn-primary" style="display: inline"><i class="fas fa-arrow-left"></i> BACK</button></a>
                        <button type="submit" class="btn btn-primary" style="display: inline"><i class="fas fa-save"></i> SAVE</button>
                </form>
                <form id="formsubmit" action="{{route('ot.store')}}" method="POST" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')" style="display: inline">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                    <button type="button" id="sub" class="btn btn-primary"><i class="fas fa-share-square"></i> SUBMIT</button>
                </form>
                    </div>
            @endif
            <br>
            <p><b>ACTION LOG</b></p>
            <table class="table table-bordered">
                <thead>
                    <tr class="info">
                        <th width="10%">Date</th>
                        <th width="15%">Action</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($otlog)==0)
                        <tr id="nodata" class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                    @endif
                    @foreach($otlog as $singleuser)
                    <tr>
                        <td>{{$singleuser->created_at}}</td>
                        <td>{{$singleuser->name->name}}</td>
                        <td>{{$singleuser->message}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(!(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query"))))
            <div class="text-center">
                <a href="{{route('ot.list')}}"><button type="button" class="btn btn-primary" style="display: inline"><i class="fas fa-arrow-left"></i> BACK</button></a>
            </div>
            @endif
        @endif
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
    var add = false;
    var submit = false;
    dt.setDate(dt.getDate() - 1);
    ot.setDate(ot.getDate() - 91);
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
            if(add){
                if(i==0){
                    alert("Please save current time input before adding a new one!");
                }else{
                    alert("Please save current time input before editing others!");
                }
            }else{
                add=true;
                $('#edit-'+i).css("display", "table-row");
                $('#show-'+i).css("display", "none");
            }
            if(i==0){
                $('#nodata').css("display","none");
            }
        };
    };
    
    function otx(i){
        return function(){
            if(add){
                add=false;
                $('#edit-'+i).css("display", "none");
                $('#show-'+i).css("display", "table-row");
            }
            if(i==0){
                $('#nodata').css("display","table-row");
            }
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

    //when click x in add time
    $('.otx').click(function() {
        $('#addNew').css("display", "none");
    });

    //submit form when any values are changed
    $("form .forminput").change(function(){
        $("#save").val("save");
        $("#formot").submit();
    });

    //when submit button is clicked
    @if($i ?? "")
    $("#sub").on("click ", function(){
        for(i=0; i<{{$i}}+1;i++){
            if($('.inputcheck-'+i).get(0).checkValidity()==false){
                $('.inputcheck-'+i).get(0).reportValidity();
                submit = false;
            }else{
                submit = true;
            }
        }
        if(submit){
            @if(count($otlist)!=0)
                $("#formsubmit").submit();
            @else
                alert("Please add claim time before submitting!"); 
            @endif
        }
    }); 
    @endif
</script>
@stop