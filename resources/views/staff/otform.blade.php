@extends('adminlte::page')

@section('title', 'Overtime Form')

@section('content')

@if($claim ?? '')
    @if(($claim->status=="D1")||($claim->status=="D2"))
        @php($c = true)
    @elseif(($claim->status=="Q1")||($claim->status=="Q2"))
        @php($q = true)
    @endif
@elseif($draft ?? '')
    @php($d = true)
@endif

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
    <div class="panel-heading panel-primary">OT Application List 
        @if($claim ?? '') 
            {{date('d/m/Y', strtotime($claim->date))}}({{date('l', strtotime($claim->date))}}) 
        @elseif($draft ?? '') 
            {{date('Y-m-d', strtotime($draft[6]))}} ({{date('l', strtotime($draft[6]))}})
        @endif
    </div>
    <div class="panel-body">
        @if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif
        <div class="row">
            <div class="col-xs-6">
                <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
                    @csrf
                    <p>Date: <input type="date" id="inputdate" name="inputdate" value=@if($claim ?? '')
                            "{{$claim->date}}"
                        @elseif($draft ?? '')
                            "{{date('Y-m-d', strtotime($draft[6]))}}"
                        @endif required>
                    </p>
                </form>
                <div>
                <p>Reference No: 
                    @if($claim ?? '') 
                        {{$claim->refno}} 
                    @elseif($draft ?? '') 
                        {{$draft[0]}} 
                    @else 
                        N/A
                    @endif
                </p>
                <p>State Calendar: </p>
                    @if(($c ?? '')||($d ?? ''))
                        @if(($claim ?? '')||($draft ?? ''))
                            <span style="color: red">
                                <p>Due Date: 
                                    @if($claim ?? '') 
                                        {{$claim->date_expiry}}
                                    @else 
                                        {{$draft[1]}} 
                                    @endif
                                </p>
                                <p>Unsubmitted claims will be deleted after the due date</p>
                            </span>
                        @else
                            <p>Charging type: {{$claim->charge_type}}</p>
                            <p>Justification: {{$claim->justification}}</p>
                        @endif
                    @elseif($q ?? '')
                        <p>Query Message: 
                            @foreach($claim->log as $logs) 
                                @if(strpos($logs->message,"Queried")!==false) 
                                    @php($query = $logs->message) 
                                @endif 
                            @endforeach 
                            {{str_replace('"', '', str_replace('Queried with message: "', '', $query))}}</p>
                    @endif
                </div>
            </div>
            <div class="col-xs-6">
                <p>Status: 
                    @if($claim ?? '')  
                        @if($c ?? '')
                            Draft 
                        @elseif ($q ?? '')
                            Query 
                        @elseif ($claim->status=="PA")
                            Pending Approval 
                        @elseif ($claim->status=="PV")
                            Pending Verification  
                        @elseif ($claim->status=="A")
                            Aproved 
                        @else 
                            {{ $claim->status }} 
                        @endif  
                    @elseif($draft ?? '') 
                        Draft 
                    @else 
                        N/A
                    @endif</p>
                <p>Verifier: 
                    @if($claim ?? '') 
                        {{$claim->verifier->name}}
                    @elseif($draft ?? '')
                        {{$draft[2]}} 
                    @else 
                        N/A 
                    @endif
                </p>
                <p>Approver: 
                    @if($claim ?? '')
                        {{$claim->approver->name}}
                    @elseif($draft ?? '')
                        {{$draft[3]}}
                    @else 
                        N/A
                    @endif
                </p>
                <p>Estimated Amount: RM
                    @if($claim ?? '') 
                        {{$claim->amount}} 
                    @else 
                        0.00
                    @endif</p>
            </div>
        </div>
        <div class="row" style="display: flex">
            <div class="col-xs-6" style="display: flex; align-items: flex-end;">
                <p><b>TIME LIST</b></p>
            </div>
            <div class="col-xs-6">
                @if($claim ?? '')
                    @if(in_array($claim->status, $array = array("D1", "D2", "Q1", "Q2")))
                        @php($c = true)
                    @endif
                @endif
                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                <div class="text-right" >
                    <button type="button" class="btn btn-primary" id="add">
                        ADD TIME
                    </button>
                    <p>Total time: 
                            @if($claim ?? "")
                                <span id="oldth" class="hidden">{{$claim->time->hour}}</span>
                                <span id="oldtm" class="hidden">{{$claim->time->minute}}</span>
                                <span id="showtime">
                                    {{$claim->time->hour}}h {{$claim->time->minute}}m
                                </span>
                            @else
                                <span id="oldth" class="hidden">{{$draft[5]->hour}}</span>
                                <span id="oldtm" class="hidden">{{$draft[5]->minute}}</span>
                                <span id="showtime">
                                    {{$draft[5]->hour}}h {{$draft[5]->minute}}m
                                </span>
                            @endif
                        / 104h
                    </p>
                </div>
                @endif
            </div>
        </div>
        <form id="form" action="{{route('ot.formsubmit')}}" method="POST">
            @csrf
            <input type="text" class="form-control hidden" id="inputid" name="inputid" value="@if($claim ?? '') {{$claim->id}} @endif" required>
            <input class="hidden" id="formnew" type="text" name="formnew" value="no">
            <input class="hidden" id="formadd" type="text" name="formsave" value="no">
            <input class="hidden" id="formsave" type="text" name="formsave" value="no">
            <input class="hidden" id="formsubmit" type="text" name="formsubmit" value="yes">
            <table class="table table-bordered">
                <thead>    
                    <tr class="info">
                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                            <th width="2%"></th>
                        @endif
                            <th width="2%">No</th>
                            <th width="20%">Clock In/Out</th>
                            <th width="20%">Start/End Time</th>
                            <th width="8%">Total Time</th>
                            <th>Justification</th>
                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                            <th width="10%">
                                Action
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if($claim ?? '')
                        @if(count($claim->detail)!=0)
                            @foreach($claim->detail as $no=>$singleuser)
                                <tr>
                                    @php($s = false)
                                    @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                        @php($s = true)
                                    @else
                                        @if($singleuser->checked=="X")
                                            @php($s = true)
                                        @endif
                                    @endif
                                    @if($s)
                                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                            <td><input class="forminput" type="checkbox" id="inputcheck-{{++$no}}" name="inputcheck[]" value="{{$singleuser->id}}"
                                                @if($singleuser->checked=="X")
                                                    checked
                                                @endif >
                                            </td>
                                        @endif
                                        <td>{{$no}}</td>
                                        <td>
                                            @if($singleuser->clock_in!="")
                                                {{date('H:i', strtotime($singleuser->clock_in))}} - {{date('H:i', strtotime($singleuser->clock_out))}} 
                                            @else 
                                                Manual 
                                            @endif
                                        </td>
                                        <td>
                                            @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                            <span id="oldds-{{$no}}" class="hidden">{{$singleuser->start_time}}</span>
                                            <span id="oldde-{{$no}}" class="hidden">{{$singleuser->end_time}}</span>
                                                <input style="width: 40px" id="inputstart-{{$no}}" name="inputstart[]" type="text" class="timepicker forminputtime" 
                                                    data-clock_in="{{ date('H:i', strtotime($singleuser->start_time))}}"
                                                    @if($singleuser->clock_in!="")
                                                        data-clocker="{{ date('H:i', strtotime($singleuser->start_time))}}"
                                                        @endif
                                                    value="{{ date('H:i', strtotime($singleuser->start_time))}}" required>
                                                <input style="width: 40px" id="inputend-{{$no}}" name="inputend[]" type="text" class="timepicker forminputtime" 
                                                {{--@if($singleuser->clock_out!="")--}}
                                                        data-clock_out="{{ date('H:i', strtotime($singleuser->end_time))}}"
                                                        {{--@endif--}}
                                                    value="{{ date('H:i', strtotime($singleuser->end_time))}}" required>
                                            @else
                                                {{ date('H:i', strtotime($singleuser->start_time)) }} - {{ date('H:i', strtotime($singleuser->end_time)) }}
                                            @endif    
                                        </td>
                                        <td>
                                            <span id="fixdh-{{$no}}" class="hidden">{{$singleuser->hour}}</span>
                                            <span id="fixdm-{{$no}}" class="hidden">{{$singleuser->minute}}</span>
                                            <span id="olddh-{{$no}}" class="hidden">{{$singleuser->hour}}</span>
                                            <span id="olddm-{{$no}}" class="hidden">{{$singleuser->minute}}</span>
                                            <span id="inputduration-{{$no}}">{{ $singleuser->hour }}h {{ $singleuser->minute }}m</span>
                                        </td>
                                        <td>
                                            @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                                <textarea class="forminput" rows = "2" cols = "60" type="text" id="inputremark-{{$no}}" name="inputremark[]" placeholder="Write justification" style="resize: none" 
                                                    @if($singleuser->clock_in!="") 
                                                        disabled
                                                    @else
                                                        required 
                                                    @endif>{{$singleuser->justification}}
                                                </textarea>
                                            @else
                                                {{$singleuser->justification}}
                                            @endif 
                                        </td>
                                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                            <td>
                                                @if($singleuser->clock_in=="")
                                                    <button type="button" class="btn btn-danger" id="delete-{{$no}}" data-id="{{$singleuser->id}}"><i class="fas fa-trash"></i></button>
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr id="nodata" class="text-center"><td colspan="7"><i>Not Available</i></td></tr>
                        @endif
                    @else
                        <tr id="nodata" class="text-center"><td colspan="7"><i>Not Available</i></td></tr>
                    @endif
                    <tr id="addform" style="display: none">
                        <td></td>
                        <td>@if($claim ?? '') {{count($claim->detail)+1}} @else 1 @endif</td>
                        <td>Manual Input</td>
                        <td>
                            <input style="width: 40px" id="inputstart-0" type="text" name="inputstartnew" class="timepicker check-0" 
                                @if(session()->get('draftform'))
                                    value="{{session()->get('draftform')[0]}}"
                                @endif>
                            <input style="width: 40px" id="inputend-0" type="text" name="inputendnew" class="timepicker check-1" 
                                @if(session()->get('draftform'))
                                    @empty(session()->get('draftform')[1])
                                        disabled
                                    @else
                                        value="{{session()->get('draftform')[1]}}"
                                    @endempty
                                @else
                                    disabled
                                @endif>
                        </td>
                        <td>
                            <span id="olddh-0" class="hidden">0</span>
                            <span id="olddm-0" class="hidden">0</span>
                            <span id="inputduration-0"></span>
                        </td>
                        <td><textarea rows = "2" cols = "60" type="text"  id="inputremark-0" name="inputremarknew" placeholder="Write justification" style="resize: none" class="check-2"></textarea></td>
                        <td>
                            <button type="button" class="btn btn-primary" id="btn-add"><i class="fas fa-save"></i></button>
                            <button type="button" class="btn btn-danger" id="cancel" style="display: inline"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>  
            </table>
            @if(($c ?? '')||($d ?? '')||($q ?? ''))
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Charge Type:</label>
                                </div>
                                <div class="col-xs-9">
                                    <select name="chargetype" class="forminput" id="chargetype" value="
                                        @if($claim ?? '')
                                            {{$claim->charge_type}}
                                        @endif" required>
                                        <option hidden disabled value="" 
                                            @if($claim ?? '') 
                                                @if($claim->charge_type=="") 
                                                    selected 
                                                @endif 
                                            @else 
                                                selected
                                            @endif>Select Charge Type
                                        </option>
                                        <option value="Cost Center" 
                                            @if($claim ?? '') 
                                                @if($claim->charge_type=="Cost Center")
                                                    selected
                                                @endif 
                                            @endif>Cost Center</option>
                                        <option value="Project" 
                                            @if($claim ?? '') 
                                                @if($claim->charge_type=="Project") 
                                                    selected 
                                                @endif 
                                            @endif>
                                            Project
                                        </option>
                                    </select> 
                                </div>
                                @if($claim ?? '')
                                    <div id="costcenter" 
                                        @if($claim->charge_type!="Cost Center") 
                                            style="display: none" 
                                        @endif>
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label>Charging:</label>
                                            </div>
                                            <div class="col-xs-9">
                                                <select name="charging" id="charging" class="forminput" 
                                                    @if($claim->charge_type=="Cost Center") 
                                                        required 
                                                    @endif>
                                                    <option value="ATAC07">ATAC07</option>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                    <div id="project" 
                                        @if($claim->charge_type!="Project")
                                            style="display: none" 
                                        @endif>
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label>Type:</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <select name="type" id="type" class="forminput" 
                                                    @if($claim->charge_type=="Project") 
                                                        required 
                                                    @endif>
                                                    <option value="CUST23234">CUST23234</option>
                                                </select> 
                                            </div>
                                            <div class="col-xs-3">
                                                <label>Header:</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <select name="header" id="header" class="forminput" 
                                                    @if($claim->charge_type=="Project")
                                                        required
                                                    @endif>
                                                    <option value="PRJ123124">PRJ123124</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label>Code:</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <select name="code" id="code" class="forminput" 
                                                    @if($claim->charge_type=="Project")
                                                        required
                                                    @endif>
                                                    <option value="PRJ123124">PRJ123124</option>
                                                </select> 
                                            </div>
                                            <div class="col-xs-3">
                                                <label>Activity:</label>
                                            </div>
                                            <div class="col-xs-3">
                                                <select name="activity" id="activity" class="forminput" 
                                                    @if($claim->charge_type=="Project") 
                                                        required
                                                    @endif>
                                                    <option value="PRJ123124">PRJ123124</option>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: -10px">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="inputremark">Justification:</label>
                                </div>
                                <div class="col-xs-9">
                                    <textarea class="forminput" rows = "5" cols = "60" id="inputremark" name="inputremark" placeholder="Write justification" required>
                                        @if($claim ?? '') 
                                            {{$claim->justification}} 
                                        @endif
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-center">
                    <a href="{{route('ot.list')}}"><button type="button" class="btn btn-primary" style="display: inline"><i class="fas fa-arrow-left"></i> BACK</button></a>
                    <button type="button" id="btn-save" class="btn btn-primary" style="display: inline"><i class="fas fa-save"></i> SAVE</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-share-square"></i> SUBMIT</button>
                </div>
            @endif
        </form>
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
                @if($claim ?? '')
                    @if($claim->log!="")
                        @if(count($claim->log)==0)
                            <tr class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                        @else
                            @foreach($claim->log as $singleuser)
                            <tr>
                                <td>{{$singleuser->created_at}}</td>
                                <td>{{$singleuser->name->name}}</td>
                                <td>{{$singleuser->message}}</td>
                            </tr>
                            @endforeach
                        @endif
                    @else
                        <tr class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                    @endif
                @elseif($draft ?? '')
                    <tr>
                        <td>{{$draft[4]}}</td>
                        <td>{{$draft[7]}}</td>
                        <td>Created draft {{$draft[0]}}</td>
                    </tr>
                @else   
                    <tr class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                @endif
            </tbody>
        </table>
        @empty($claim ?? '')
            @if(!($d ?? ''))
                <div class="text-center">
                    <a href="{{route('ot.list')}}"><button type="button" class="btn btn-primary" style="display: inline"><i class="fas fa-arrow-left"></i> BACK</button></a>
                </div>
            @endif
        @else
            @if(!(($c ?? '')||($d ?? '')||($q ?? '')))
                <div class="text-center">
                    <a href="{{route('ot.list')}}"><button type="button" class="btn btn-primary" style="display: inline"><i class="fas fa-arrow-left"></i> BACK</button></a>
                </div>
            @endif
        @endempty
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
    @if(session()->has('feedback'))
        $("#alert").css("display","block")
    @endif

    var add = true; //flag when click addtime button
    var checker = true; //since bootstrap timepicker do onchange twice
    var submit = false; //check validation before adding new time
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

    //apply timepicker plugin to all time input
    $('.timepicker').timepicker({
        minuteStep: 1,
        showMeridian: false,
        defaultTime: false
    });

    @if(($c ?? '')||($d ?? '')||($q ?? ''))
        //check start time & end time
        function killview(i, m, s, e){
            alert(m);
            if(i!=0){
                $("#inputstart-"+i).val(s);
                $("#inputend-"+i).val(e);
            }else{
                $("#inputstart-"+i).val("");
                $("#inputend-"+i).val("");
                $("#inputend-"+i).prop('disabled', true);
                $("#inputduration-"+i).text("");
            }
            return false;
        }

        function timemaster(st, et){
            var starto = st.split(":");
            var endo = et.split(":");
            var nstarto = ((parseInt(starto[0]))*60)+(parseInt(starto[1]));
            var nendo = ((parseInt(endo[0]))*60)+(parseInt(endo[1]));
            var time = [nstarto, nendo];
            return time;
        }

        function showtime(total){
            var dh = 0;
            var dm = total;
            while(total>=60){
                dh++;
                total=total-60;
                dm=total;
            }
            var hm = [dh, dm]
            return hm;
        }

        function calshowtime(i, total, odh, odm, th, tm){
            hm = showtime(total); //input
            if((hm[0]==0)&&(hm[1]==0)){
                $("#inputduration").text("");
            }else{
                $("#inputduration-"+i).text(hm[0]+"h "+hm[1]+"m");
            }
            nhm = showtime((parseInt(th*60)+parseInt(tm))+(parseInt(hm[0]*60)+parseInt(hm[1]))-(parseInt(odh*60)+parseInt(odm)));
            $("#olddh-"+i).text(hm[0]);
            $("#olddm-"+i).text(hm[1]);
            $("#oldth").text(nhm[0]);
            $("#oldtm").text(nhm[1]);
            $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
        }

        function checktime(i){
            return function(){
                var clock_in = $("#inputstart-"+i).data('clock_in');
                var clock_out = $("#inputend-"+i).data('clock_out');
                if(i!=0){
                    if($("#inputstart-"+i).val()==""){
                        $("#inputstart-"+i).val(clock_in);
                    }else if($("#inputend-"+i).val()==""){
                        $("#inputend-"+i).val(clock_out);
                    }
                }
                var clocker = $("#inputstart-"+i).data('clocker');
                if(checker){
                    if(clock_in!=""){
                        if($("#inputstart-"+i).val()==""){
                            $("#inputstart-"+i).val(clock_in);
                        }else if($("#inputend-"+i).val()==""){
                            $("#inputend-"+i).val(clock_out);
                        }
                    }
                    checker = false;
                }else{
                    var check=true;
                    var time=[];
                    var st = ($("#inputstart-"+i).val()).split(":");
                    var min = "{{$day[0]}}";
                    var max = "{{$day[1]}}";
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
                    var nstart = ((parseInt(mt[0]))*60)+(parseInt(mt[1]));
                    var nend = ((parseInt(mxt[0]))*60)+(parseInt(mxt[1]));
                    if($("#inputend-"+i).val()!=""){
                        var et = ($("#inputend-"+i).val()).split(":");
                        var end = ((parseInt(et[0]))*60)+(parseInt(et[1]));
                    }
                    if(clocker!=undefined){
                        time = timemaster(clock_in, clock_out);
                        if(check){
                            if((time[0]<=start&&time[1]>=start)&&(time[0]<=end&&time[1]>=end)){
                                calshowtime(i, end-start, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }else{
                                check = killview(i, "Time input must be within time range from "+clock_in+" to "+clock_out+"!", clock_in, clock_out);
                                calshowtime(i, (parseInt($("#fixdh-"+i).text()*60)+parseInt($("#fixdm-"+i).text())), $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }
                        }   
                    }else{
                        if($("#inputstart-"+i).val()!=""){
                            $("#inputend-"+i).prop('disabled', false);
                        }else{
                            $("#inputend-"+i).prop('disabled', true);
                        }
                        @if($claim ?? '')
                            @foreach($claim->detail as $singleuser)
                                time = timemaster("{{date("H:i", strtotime($singleuser->start_time))}}", "{{date("H:i", strtotime($singleuser->end_time))}}");
                                if(check){
                                    if(start > time[0] && start < time[1]){
                                        if(i!=0){
                                            if(!("{{date("H:i", strtotime($singleuser->start_time))}}"==clock_in)){
                                                check = killview(i, "Time input cannot be within inserted time range!", clock_in, clock_out);
                                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());   
                                            }
                                        }
                                        else{
                                            check = killview(i, "Time input cannot be within inserted time range!", clock_in, clock_out);  
                                            calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());  
                                        }
                                    }
                                }
                            @endforeach
                        @endif
                        if(check){
                            if(start > nstart && start < nend){
                                check = killview(i, "Time input cannot be between {{$day[0]}} and {{$day[1]}}!", clock_in, clock_out);
                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }
                        }
                        if($("#inputstart-"+i).val()!="" && $("#inputend-"+i).val()!=""){
                            if(start<end){
                                @if($claim ?? '')
                                    @foreach($claim->detail as $singleuser)
                                        time = timemaster("{{date("H:i", strtotime($singleuser->start_time))}}", "{{date("H:i", strtotime($singleuser->end_time))}}");
                                        if(check){
                                            if((time[0]<end&&time[1]>start)){
                                                if(i!=0){
                                                    if(!("{{date("H:i", strtotime($singleuser->start_time))}}"==clock_in)){
                                                        check = killview(i, "Time input cannot be within inserted time range!", clock_in, clock_out);
                                                        calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                                    }
                                                }else{
                                                    check = killview(i, "Time input cannot be within inserted time range!", clock_in, clock_out);
                                                    calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                                }
                                            }
                                        }
                                    @endforeach
                                @endif
                                if(check){
                                    if((end>nstart && end<nend)||(nstart<end && nend>start)){
                                        check = killview(i, "Time input cannot be between {{$day[0]}} and {{$day[1]}}!");
                                        calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                    }else{
                                        calshowtime(i, end-start, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                        // if(i!=0){
                                        //     $("#formchange").val("yes");
                                        //     $("#formautosave").val("yes");
                                        //     $("#formsubmit").submit();
                                        // }
                                    }
                                }
                            }else{
                                alert("End time must be more than "+sh+":"+sm+me+"!");
                                if(i==0){
                                    $("#inputend-"+i).val("");
                                }else{
                                    $("#inputend-"+i).val(clock_out);
                                }
                            }
                        }
                    } 
                    checker = true;        
                }                   
            };
        };

        function checkbox(i){
            return function(){
                if ($('#inputcheck-'+i).is(':checked')){
                    calshowtime(i, (parseInt($("#olddh-"+i).text()*60)+parseInt($("#olddm-"+i).text())), 0, 0, $("#oldth").text(), $("#oldtm").text());
                }else{
                    nhm = showtime((parseInt($("#oldth").text()*60)+parseInt($("#oldtm").text()))-(parseInt($("#olddh-"+i).text()*60)+parseInt($("#olddm-"+i).text())));
                $("#oldth").text(nhm[0]);
                $("#oldtm").text(nhm[1]);
                $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
                }
            }
        }

        for(i=0; i<
            @if($claim ?? '') 
                {{count($claim->detail)+1}} 
            @else 
                1 
            @endif; i++) {
            $("#inputstart-"+i).change(checktime(i));
            $("#inputend-"+i).change(checktime(i));
            $("#inputcheck-"+i).change(checkbox(i));
        };
        
        //when click add time
        $("#add").on('click', function(){
            if(add){
                $("#formnew").val("new");
                $('#addform').css("display", "table-row");
                $("#inputnew").val("new");
                $("#inputstart-0").prop('required',true)
                $("#inputend-0").prop('required',true)
                $("#inputremark-0").prop('required',true);
                calshowtime(0, (parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())), 0, 0, $("#oldth").text(), $("#oldtm").text());
                $('#nodata').css("display","none");
                add=false;  
            }else{
                alert("Please save current time input before adding a new one!");
            }
        });
        
        //when cancel add time
        $("#cancel").on('click', function(){
            if(!(add)){
                $("#formnew").val("no");
                $('#addform').css("display", "none");
                $("#inputstart-0").prop('required',false)
                $("#inputend-0").prop('required',false)
                $("#inputremark-0").prop('required',false);
                nhm = showtime((parseInt($("#oldth").text()*60)+parseInt($("#oldtm").text()))-(parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())));
                $("#oldth").text(nhm[0]);
                $("#oldtm").text(nhm[1]);
                $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
                $('#nodata').css("display","table-row");
                add=true;  
            }
        });
    @endif

    //when adding new time
    $("#btn-add").on('click', function(){
        for(i=0; i<3;i++){
            if($('.check-'+i).get(0).checkValidity()==false){
                // $('.check-2').get(0).reportValidity();
                $('.check-'+i).get(0).reportValidity();
                submit = false;
            }else{
                submit = true;
            }
        }
        if(submit){
            $("#formadd").val("yes");
            $("#formsubmit").val("yes");
            $("#form").submit();
        }
    });     

    // $("form .forminput").change(function(){
    //     $("#formchange").val("yes");
    //     $("#formautosave").val("yes");
    //     $("#formsubmit").submit();
    // });    
</script>
@stop
           