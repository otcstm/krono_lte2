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
        <form id="formsave" action="{{route('ot.formsave')}}" method="POST">
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
                                            <td><input type="checkbox" id="inputcheck-{{++$no}}" name="inputcheck[]" value="{{$singleuser->id}}"
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
                                                <input style="width: 40px" id="inputstart-{{$no}}" name="inputstart[]" type="text" class="timepicker" 
                                                    @if($singleuser->clock_in!="")
                                                        data-clock_in="{{ date('H:i', strtotime($singleuser->start_time))}}"
                                                    @endif
                                                    value="{{ date('H:i', strtotime($singleuser->start_time))}}" required>
                                                <input style="width: 40px" id="inputend-{{$no}}" name="inputend[]" type="text" class="timepicker" 
                                                    @if($singleuser->clock_out!="")
                                                        data-clock_out="{{ date('H:i', strtotime($singleuser->end_time))}}"
                                                    @endif
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
                                                <textarea rows = "2" cols = "60" type="text"  id="inputremark-{{$no}}" name="inputremark[]" placeholder="Write justification" style="resize: none" 
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
                            <input class="hidden" id="inputnew" type="text" name="inputnew" value="">
                            <input style="width: 40px" id="inputstart-0" type="text" name="inputstartnew" class="timepicker">
                            <input style="width: 40px" id="inputend-0" type="text" name="inputendnew" class="timepicker" disabled>
                        </td>
                        <td>
                            <span id="olddh-0" class="hidden">0</span>
                            <span id="olddm-0" class="hidden">0</span>
                            <span id="inputduration-0"></span>
                            </td>
                        <td><textarea rows = "2" cols = "60" type="text"  id="inputremark-0" name="inputremarknew" placeholder="Write justification" style="resize: none"></textarea></td>
                        <td>
                            <button type="button" class="btn btn-primary" id="save"><i class="fas fa-save"></i></button>
                            <button type="button" class="btn btn-danger" id="cancel" style="display: inline"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>  
            </table>
            <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i></button>
        </form>
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
                @if($claim ?? '')
                    @if($claim->log!="")
                        @if(count($claim->log)==0)
                            <tr class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
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
                        <tr class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                    @endif
                @else   
                    <tr class="text-center"><td colspan="3"><i>Not Available</i></td></tr>
                @endif
            </tbody>
        </table>
        @if($claim ?? '')
            @if(!(in_array($claim->status, $array = array("D1", "D2", "Q1", "Q2"))))
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

    var add = true; //flag when click addtime button
    var checker = true; //since bootstrap timepicker do onchange twice
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
        function killview(i, m, t, s, e){
            alert(m);
            if(t){
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
                    if(clock_in!=undefined){
                        time = timemaster(clock_in, clock_out);
                        if(check){
                            if((time[0]<=start&&time[1]>=start)&&(time[0]<=end&&time[1]>=end)){
                                calshowtime(i, end-start, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }else{
                                check = killview(i, "Time input must be within time range from "+clock_in+" to "+clock_out+"!", true, clock_in, clock_out);
                                calshowtime(i, (parseInt($("#fixdh-"+i).text()*60)+parseInt($("#fixdm-"+i).text())), $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }
                        }   
                    }else{
                        if($("#inputstart-"+i).val()!=""){
                            $("#inputend-"+i).prop('disabled', false);
                            $("#inputend-"+i).prop('required',true)
                        }else{
                            $("#inputend-"+i).prop('disabled', true);
                            $("#inputend-"+i).prop('required',false)
                        }
                        @if($claim ?? '')
                            @foreach($claim->detail as $singleuser)
                                time = timemaster("{{date("H:i", strtotime($singleuser->start_time))}}", "{{date("H:i", strtotime($singleuser->end_time))}}");
                                if(check){
                                    if(start > time[0] && start < time[1]){
                                        check = killview(i, "Time input cannot be within inserted time range!", false, "", "");  
                                        calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());  
                                    }
                                }
                            @endforeach
                        @endif
                        if(check){
                            if(start > nstart && start < nend){
                                check = killview(i, "Time input cannot be between {{$day[0]}} and {{$day[1]}}!", false, "", "");
                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }
                        }
                        if($("#inputstart-"+i).val()!="" && $("#inputend-"+i).val()!=""){
                            if(start<end){
                                @if($claim ?? '')
                                    @foreach($claim->detail as $singleuser)
                                        time = timemaster("{{date("H:i", strtotime($singleuser->start_time))}}", "{{date("H:i", strtotime($singleuser->end_time))}}");
                                        // if((end > time[0] && end < time[1])||(time[0]<end && time[1]>start)){
                                        if(check){
                                            if((time[0]<end&&time[1]>start)){
                                                check = killview(i, "Time input cannot be within inserted time range!", false, "", "");
                                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
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
                                    }
                                }
                            }else{
                                alert("End time must be more than "+sh+":"+sm+me+"!");
                                $("#inputend-"+i).val("");
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
                    calshowtime(0, (parseInt($("#olddh-"+i).text()*60)+parseInt($("#olddm-"+i).text())), 0, 0, $("#oldth").text(), $("#oldtm").text());
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
                $('#addform').css("display", "table-row");
                $("#inputnew").val("new");
                $("#inputstart-0").prop('required',true)
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
            if(add){
                $('#nodata').css("display","table-row");
            }else{
                $('#addform').css("display", "none");
                $("#inputnew").val("");
                $("#inputstart-0").prop('required',false)
                $("#inputremark-0").prop('required',false);
                nhm = showtime((parseInt($("#oldth").text()*60)+parseInt($("#oldtm").text()))-(parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())));
                $("#oldth").text(nhm[0]);
                $("#oldtm").text(nhm[1]);
                $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
                add=true;  
            }
        });
    @endif


    $("#timepicker1").change(function(){
    });
    
</script>
@stop
           