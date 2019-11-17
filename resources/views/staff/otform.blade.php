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
    <div class="panel-heading panel-primary">OT Application List @if($claim ?? '') {{$claim->date}} ({{date('l', strtotime($claim->date))}}) @elseif($draft ?? '') {{date('Y-m-d', strtotime($draft[6]))}}  ({{date('l', strtotime($draft[6]))}}) @endif</div>
    <div class="panel-body">
        @if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif
        <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
            @csrf
            <p>Date: <input type="date" id="inputdate" name="inputdate" value="@if($claim ?? ''){{$claim->date}}@elseif($draft ?? ''){{date('Y-m-d', strtotime($draft[6]))}}@endif" required></p>
        </form>
        <div class="row">
            <div class="col-xs-6">
                <p>Reference No: @if($claim ?? '') {{$claim->refno}} @elseif($draft ?? '') {{$draft[0]}} @else Null @endif</p>
                <p>State Calendar: </p>
                @if($claim ?? '')
                    @if(($claim->status=="Draft (Incomplete)")||($claim->status=="Draft (Complete)"))
                        @php($c = true)
                    @elseif(($claim->status=="Query (Incomplete)")||($claim->status=="Query (Complete)"))
                        @php($q = true)
                    @endif
                @elseif($draft ?? '')
                    @php($d = true)
                @endif
                @if(($c ?? '')||($d ?? ''))
                    @if(($claim ?? '')||($draft ?? ''))
                        <span style="color: red"><p>Due Date: @if($claim ?? '') {{$claim->date_expiry}} @else {{$draft[1]}} @endif</p>
                        <p>Unsubmitted claims will be deleted after the due date</p></span>
                    @else
                        <p>Charging type: {{$claim->charge_type}}</p>
                        <p>Justification: {{$claim->justification}}</p>
                    @endif
                @elseif($q ?? '')
                    <p>Query Message: @foreach($claim->log as $logs) @if(strpos($logs->message,"Query")!==false) @php($query = $logs->message) @endif @endforeach @if(($claim->status=="Query (Complete)")||($claim->status=="Query (Incomplete)")){{str_replace('")', '', str_replace('Query ("', '', $query))}}@endif</p>
                @endif
            </div>
            <div class="col-xs-6">
                <p>Status: @if($claim ?? '')  @if(($claim->status=="Draft (Complete)")||($claim->status=="Draft (Incomplete)"))Draft @elseif (($claim->status=="Query (Complete)")||($claim->status=="Query (Incomplete)"))Query @else {{ $claim->status }} @endif  @elseif($draft ?? '') Draft @else Null @endif</p>
                <p>Verifier: @if($claim ?? '') {{$claim->verifier->name}}  @elseif($draft ?? '') {{$draft[2]}} @else Null @endif</p>
                <p>Approver: @if($claim ?? '') {{$claim->approver->name}}  @elseif($draft ?? '') {{$draft[3]}} @else Null @endif</p>
                <p>Estimated Amount: RM @if($claim ?? '') {{$claim->amount}} @else 0.00 @endif</p>
            </div>
        </div>
        <div class="row" style="display: flex">
            <div class="col-xs-6" style="display: flex; align-items: flex-end;">
                <p><b>TIME LIST</b></p>
            </div>
            <div class="col-xs-6">
                @if($claim ?? '')
                    @if(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query (Incomplete)", "Query (Complete)")))
                        @php($c = true)
                    @endif
                @endif
                @if(($c ?? '')||($d ?? ''))
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
                    @if(($c ?? '')||($d ?? ''))
                    <th width="10%">
                        Action
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if($otlist ?? '')
                    @foreach($otlist as $no=>$singleuser)
                    <tr id="show-{{++$no}}">
                        <td>{{$no }}</td>
                        <td></td>
                        <td>{{ date('H:i', strtotime($singleuser->start_time)) }} - {{ date('H:i', strtotime($singleuser->end_time)) }}</td>
                        <td>{{ $singleuser->hour }}h {{ $singleuser->minute }}m</td>
                        <td>{{ $singleuser->justification }}</td>
                        @if(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query (Complete)", "Query (Incomplete)")))
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
                @endif
                @if(($claim ?? '')==""||($draft ?? ''))
                    <tr id="nodata" class="text-center"><td colspan="6"><i>Not Available</i></td></tr>
                @endif
                <tr id="edit-0" style="display: none">
                    <form action="{{route('ot.formadd')}}" method="POST">
                        @csrf
                        <input type="text" class="form-control hidden" id="inputid" name="inputid" value="@if($claim ?? '') {{$claim->id}} @endif">
                        <input type="text" class="form-control hidden" id="inputdate" name="inputdate" value="@if($claim ?? '') {{$claim->date}} @endif">
                        <input type="text" class="form-control hidden" id="claimcharge" name="claimcharge" value="@if($claim ?? '') {{$claim->charge_type}} @endif">
                        <input type="text" class="form-control hidden" id="claimremark" name="claimremark" value="@if($claim ?? '') {{$claim->justification}} @endif">
                        <td>@if($otlist ?? '') {{count($otlist)+1}} @else 1 @endif</td>
                        <td>
                            <select name="inputclock" id="inputclock-0" required>
                                <option hidden disabled selected value="">Select Time</option>
                                <option value="na">N/A</option>
                            </select>
                        </td>
                        <td>
                            <input type="time"  id="inputstart-0" name="inputstart" disabled="true">
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
        @if($claim ?? '')
            @if(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query (Complete)", "Query (Incomplete)")))
                @php($c = true)
            @endif
        @elseif($draft ?? '')
            @php($d = true)
        @endif
        @if(($c ?? '')||($d ?? ''))
            <form id="formot" action="{{route('ot.save')}}" method="POST">
                <div class="row">
                    <div class="col-xs-6">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control hidden" id="inputid" name="inputid" value="@if($claim ?? '') {{$claim->id}}@endif" required>
                            <input type="text" class="form-control hidden" id="save" name="save" value="submit" required>
                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Charge Type:</label>
                                </div>
                                <div class="col-xs-9">
                                    <select name="chargetype" class="forminput inputcheck-{{$i=0}}" id="chargetype" value="@if($claim ?? '') {{$claim->charge_type}} @endif" required>
                                        <option hidden disabled value="" @if($claim ?? '') @if($claim->charge_type=="") selected @endif @else selected @endif>Select Charge Type</option>
                                        <option value="Cost Center" @if($claim ?? '') @if($claim->charge_type=="Cost Center") selected @endif @endif>Cost Center</option>
                                        <option value="Project" @if($claim ?? '') @if($claim->charge_type=="Project") selected @endif @endif>Project</option>
                                    </select> 
                                </div>
                            </div>
                            @if($claim ?? '')
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
                            @endif
                        </div>
                        <div class="form-group" style="margin-top: -10px">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="inputremark">Justification:</label>
                                </div>
                                <div class="col-xs-9">
                                    <textarea class="forminput inputcheck-{{++$i}}" rows = "5" cols = "60" id="inputremark" name="inputremark" placeholder="Write justification" required>@if($claim ?? '') {{$claim->justification}} @endif</textarea>
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
                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="@if($claim ?? '') {{$claim->id}} @endif" required>
                <button type="button" id="sub" class="btn btn-primary"><i class="fas fa-share-square"></i> SUBMIT</button>
            </form>
                </div>
        @endif
        @if(($c ?? '')||($d ?? ''))
        <br>
        @endif
        <p><b>ACTION LOG</b></p>
        <table class="table table-bordered">
            <thead>
                <tr class="info">
                    <th width="10%">Date</th>
                    <th width="25%">Action</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @if($otlog ?? '')
                    @if(count($otlog)==0)
                        <tr id="nodata" class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                    @else
                        @foreach($otlog as $singleuser)
                        <tr>
                            <td>{{$singleuser->created_at}}</td>
                            <td>{{$singleuser->name->name}}</td>
                            <td>{{$singleuser->message}}</td>
                        </tr>
                        @endforeach
                    @endif
                @elseif($draft ?? '')
                    <tr>
                        <td>{{$draft[4]}}</td>
                        <td>{{$draft[7]}}</td>
                        <td>Created draft {{$draft[0]}}</td>
                    </tr>
                @else
                    <tr id="nodata" class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                @endif
            </tbody>
        </table>
        @if($claim ?? '')
            @if(!(in_array($claim->status, $array = array("Draft (Incomplete)", "Draft (Complete)", "Query (Complete)", "Query (Incomplete)"))))
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

    var add = false;
    var submit = false;

    //set min and max date
    var today = new Date();
    var m = today.getMonth()+1;
    var y = today.getFullYear();
    var d = today.getDate().toString();
    var minm = today.getMonth()-1;
    if (minm<0){
        minm=minm+12;
        miny=y-1;
    }else{
        miny=y;
    }
    if(m < 10){
        m = "0"+m;
    }
    if(minm < 10){
        minm = "0"+minm;
    }
    while(d.length<2){
        d = "0"+d;
    }
    $("#inputdate").attr("min", miny+"-"+minm+"-01");
    $("#inputdate").attr("max", y+"-"+m+"-"+d);

    // //when date input is changed
    $("#inputdate").change(function(){
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        if(
            ((Date.parse($("#inputdate").val()))<=Date.parse(monthNames[m-1]+" "+d+", "+y+" 23:59:59"))&&
            ((Date.parse($("#inputdate").val()))>=Date.parse(monthNames[minm-1]+" 01, "+miny+" 00:00:00"))
            ){
            $("#formdate").submit();
        }else{
            alert("Claim date must be between "+miny+"-"+minm+"-01 and "+y+"-"+m+"-"+d+"!");
            @if($show ?? '')
                $("#inputdate").val("{{$claim->date}}");
            @else
                $("#inputdate").val("");
            @endif
        }
    });

    //when choose NA
    function clock(i){
        return function(){
            if($("#inputclock-"+i).val()=='na'){
                $("#inputstart-"+i).prop('disabled', false);
                // $("#inputend-"+i).prop('disabled', false);
                $("#inputstart-"+i).prop('required',true);
                $("#inputend-"+i).prop('required',true);
                // $("#inputclock-"+i).prop('disabled', true);
                $("#inputclock-"+i).prop('required',false);
            }else{
                $("#inputstart-"+i).prop('disabled', true);
                $("#inputend-"+i).prop('disabled', true);
                $("#inputstart-"+i).prop('required',false);
                $("#inputend-"+i).prop('required',false);
                $("#inputstart-"+i).val("");
                $("#inputend-"+i).val("");
                // $("#inputclock-"+i).prop('disabled', false);
                $("#inputclock-"+i).prop('required',true);
            }
        };
    };

    @if(($c ?? '')||($d ?? ''))
    //check start time & end time
    function checktime(i){
        return function(){
            // alert($("#inputstart-"+i).val());
            if($("#inputstart-"+i).val()!=""){
                $("#inputend-"+i).prop('disabled', false);
            }else{
                $("#inputend-"+i).prop('disabled', true);
            }
            var st = ($("#inputstart-"+i).val()).split(":");
            var et = ($("#inputend-"+i).val()).split(":");
            var min = "{{$dt[0]}}";
            var max = "{{$dt[1]}}";
            var mt = min.split(":");
            var mxt = max.split(":");
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
            var nstart = ((parseInt(mt[0]))*60)+(parseInt(mt[1]));
            var nend = ((parseInt(mxt[0]))*60)+(parseInt(mxt[1]));
            if(start > nstart && start < nend){
                alert("Time input cannot be between {{$dt[0]}} and {{$dt[1]}}!");
                $("#inputstart-"+i).val("");
                $("#inputend-"+i).val("");
                $("#inputend-"+i).prop('disabled', true);
            }
            if($("#inputstart-"+i).val()!="" && $("#inputend-"+i).val()!=""){
                if(start<end){
                    if((end>nstart && end<nend)||(nstart<end && nend>start)){
                        alert("Time input cannot be between {{$dt[0]}} and {{$dt[1]}}!");
                        $("#inputend-"+i).val("");
                    }else{
                        var total = end-start;
                        var dh = 0;
                        var dm = total;
                        while(total>=60){
                            dh++;
                            total=total-60;
                            dm=total;
                        }
                        $("#inputduration-"+i).text(dh+"h "+dm+"m");
                    }
                }else{
                    alert("End time must be more than "+sh+":"+sm+me+"!");
                    $("#inputend-"+i).val("");
                }
            }
        };
    };
    @endif

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
    
    for (i=0; i<@if($otlist ?? '') {{count($otlist)+1}} @else 1 @endif; i++) {
        $("#inputclock-"+i).change(clock(i));
        $("#inputstart-"+i).change(checktime(i));
        $("#inputend-"+i).change(checktime(i));
        $("#otedit-"+i).on('click',otedit(i));
        $("#otx-"+i).on('click',otx(i));
    };

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
            @if($otlist ?? '')
                @if(count($otlist)!=0)
                    $("#formsubmit").submit();
                @else
                    alert("Please add claim time before submitting!"); 
                @endif
            @else
                alert("Please add claim time before submitting!"); 
            @endif
        }
    });
    @endif 
</script>
@stop
           