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
                        <button type="button" id="btn-date" class="btn btn-primary" style="padding: 2px 3px; margin: 0; margin-top: -3px;"><i class="fas fa-share-square"></i></button>
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
        <form id="form" action="{{route('ot.formsubmit')}}" method="POST" onsubmit="return submission()">
            @csrf
            <input type="text" class="form-control hidden" id="inputid" name="inputid" value="@if($claim ?? '') {{$claim->id}} @endif">
            <!-- <input class="hidden" id="formnew" type="text" name="formnew" value="no"> -->
            <input class="hidden" id="formadd" type="text" name="formadd" value="no">
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
                                        @if($singleuser->checked=="Y")
                                            @php($s = true)
                                        @endif
                                    @endif
                                    @if($s)
                                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                            <td><input type="checkbox" id="inputcheck-{{++$no}}"
                                                @if($singleuser->checked=="Y")
                                                    checked
                                                @endif >
                                                <input type="text" class="hidden" id="inputcheckdata-{{$no}}" name="inputcheck[]" value="{{$singleuser->checked}}">
                                            </td>
                                        @else
                                            @php(++$no)
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
                                            <span id="oldds-{{$no}}" class="hidden">{{date('H:i', strtotime($singleuser->start_time))}}</span>
                                            <span id="oldde-{{$no}}" class="hidden">{{date('H:i', strtotime($singleuser->end_time))}}</span>
                                                <input style="width: 40px" id="inputstart-{{$no}}" name="inputstart[]" type="text" class="timepicker" 
                                                    data-clock_in="{{ date('H:i', strtotime($singleuser->clock_in))}}"
                                                    data-start_time="{{ date('H:i', strtotime($singleuser->start_time))}}"
                                                    @if($singleuser->clock_in!="")
                                                        data-clocker="{{ date('H:i', strtotime($singleuser->start_time))}}"
                                                    @endif
                                                    value="{{ date('H:i', strtotime($singleuser->start_time))}}" required>
                                                <input style="width: 40px" id="inputend-{{$no}}" name="inputend[]" type="text" class="timepicker" 
                                                    data-clock_out="{{ date('H:i', strtotime($singleuser->clock_out))}}"
                                                    data-end_time="{{ date('H:i', strtotime($singleuser->end_time))}}"
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
                                                <textarea rows = "2" cols = "60" type="text" id="inputremark-{{$no}}" name="inputremark[]" placeholder="Write justification" style="resize: none" @if($singleuser->clock_in!="") readonly @else required @endif >{{$singleuser->justification}}</textarea>
                                            @else
                                                {{$singleuser->justification}}
                                            @endif 
                                        </td>
                                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                            <td>
                                                @if($singleuser->clock_in=="")
                                                    <button type="button" class="btn btn-danger" id="delete-{{$no}}" data-id="{{$singleuser->id}}" data-start="{{date('H:i', strtotime($singleuser->start_time))}}" data-end="{{date('H:i', strtotime($singleuser->end_time))}}"><i class="fas fa-trash"></i></button>
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
                            <span id="oldds-0" class="hidden">0</span>
                            <span id="oldde-0" class="hidden">0</span>
                            <input style="width: 40px" id="inputstart-0" type="text" name="inputstartnew" class="timepicker check-0">
                            <input style="width: 40px" id="inputend-0" type="text" name="inputendnew" class="timepicker check-1" disabled>
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
                            <div style="margin-top: -15px"><small>
                                <p>* Accepted format JPG, JPEG, PDF only
                                <br>* Maximum size of supporting document is 1MB
                                <br>* Make sure your PDF document is <u>not password protected</u> and <u>not corrupted</u> </p>
                            </small></div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Document*:</label>
                                </div>
                                <div class="col-xs-9">
                                    <input type="file" name="inputfile" id="inputfile" accept="image/*, .pdf, .png, .jpeg, .jpg" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Charge Type:</label>
                                </div>
                                <div class="col-xs-9">
                                    <select name="chargetype" class="chargetype" id="chargetype" value="
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
                                            <select name="charging" id="charging" 
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
                                            <select name="type" id="type" 
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
                                            <select name="header" id="header" 
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
                                            <select name="code" id="code" 
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
                                            <select name="activity" id="activity" 
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
                        <div class="form-group" style="margin-top: -10px">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="inputjustification">Justification:</label>
                                </div>
                                <div class="col-xs-9">
                                    <textarea rows = "5" cols = "60" id="inputjustification" name="inputjustification" placeholder="Write justification" required >@if($claim ?? ''){{$claim->justification}}@endif</textarea>
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
        <form id="delete" class="hidden" action="{{route('ot.formdelete')}}" method="POST">
            @csrf
            <input type="text" id="delid" name="delid" value="">
        </form>
        <p><b>ACTION LOG</b></p>
        <table id="TLog" class="table table-bordered">
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
        $("#alert").css("display","block");
    @endif

    @if(session()->has('error'))
    Swal.fire(
        'Failed to submit!',
        'Your submitted claim time has exceeded eligible claim time',
        'error'
    )
    @endif

        // $('#TLog').DataTable({
        //     "searching": false,
        //     "bSort": false,
        //     "responsive": "true",
        //     "bLengthChange": false,
        //     "order" : [[0, "asc"]],
        // });

    var add = true; //flag when click addtime button
    var checker = true; //since bootstrap timepicker do onchange twice
    var submit = false; //check validation before adding new time
    var whensubmit = true;
    //set min and max date
    var today = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
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
    // $("#inputdate").change(function(){
    $("#btn-date").on('click', function(){
        
        if(
            ((Date.parse($("#inputdate").val()))<=Date.parse(monthNames[m-1]+" "+d+", "+y+" 23:59:59"))&&
            ((Date.parse($("#inputdate").val()))>=Date.parse(monthNames[minm-1]+" 01, "+miny+" 00:00:00"))
            ){
            $("#formdate").submit();
        }
    });
        
    $("#inputdate").change(function(){
        if(
            !(((Date.parse($("#inputdate").val()))<=Date.parse(monthNames[m-1]+" "+d+", "+y+" 23:59:59"))&&
            ((Date.parse($("#inputdate").val()))>=Date.parse(monthNames[minm-1]+" 01, "+miny+" 00:00:00")))
            ){
            Swal.fire(
                'Invalid date input!',
                "Claim date must be between "+miny+"-"+minm+"-01 and "+y+"-"+m+"-"+d+"!",
                'error'
            )
            // alert("Claim date must be between "+miny+"-"+minm+"-01 and "+y+"-"+m+"-"+d+"!");
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
        maxHours: 24,
        showMeridian: false,
        defaultTime: false
    });


    @if(($c ?? '')||($d ?? '')||($q ?? ''))
        //check start time & end time
        function killview(i, m, s, e){
            // alert(m);
            Swal.fire({
                icon: 'error',
                title: 'Input time error',
                text: m
            })
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
            // alert(st+" "+et);
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
                var start_time = $("#inputstart-"+i).data('start_time');
                var end_time = $("#inputend-"+i).data('end_time');
                // alert($("#inputend-"+i).val());
                if(i!=0){
                    if($("#inputstart-"+i).val()==""){
                        $("#inputstart-"+i).val(start_time);
                    }else if($("#inputend-"+i).val()==""){
                        $("#inputend-"+i).val(end_time);
                    }
                }
                var clocker = $("#inputstart-"+i).data('clocker');
                if(checker){
                    if(clock_in!=""){
                        if($("#inputstart-"+i).val()==""){
                            $("#inputstart-"+i).val(start_time);
                        }else if($("#inputend-"+i).val()==""){
                            $("#inputend-"+i).val(end_time);
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
                    sh = h.toString();
                    while(sh.length<2){
                        sh = "0"+sh;
                    }
                    sm = m.toString();
                    while(sm.length<2){
                        sm = "0"+sm;
                    }
                    // var me = "AM";
                    // if(h>12){
                    //     h = h-12;
                    //     me = "PM"
                    // }else if(h==0){
                    //     h = 12;
                    // }
                    // sh = h.toString();
                    // while(sh.length<2){
                    //     sh = "0"+sh;
                    // }
                    // sm = m.toString();
                    // while(sm.length<2){
                    //     sm = "0"+sm;
                    // }
                    var start = ((parseInt(st[0]))*60)+(parseInt(st[1]));
                    var nstart = ((parseInt(mt[0]))*60)+(parseInt(mt[1]));
                    var nend = ((parseInt(mxt[0]))*60)+(parseInt(mxt[1]));
                    if($("#inputend-"+i).val()!=""){
                        if($("#inputend-"+i).val()=="0:00"){
                            var entime = "24:00"
                        }else{
                            var entime = $("#inputend-"+i).val();
                        }
                        var et = entime.split(":");
                        // var et = ($("#inputend-"+i).val()).split(":");
                        var end = ((parseInt(et[0]))*60)+(parseInt(et[1]));
                    }
                    if(clocker!=undefined){
                        time = timemaster(clock_in, clock_out);
                        if(check){
                            if((time[0]<=start&&time[1]>=start)&&(time[0]<=end&&time[1]>=end)){
                                calshowtime(i, end-start, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }else{
                                check = killview(i, "Time input must be within time range from "+clock_in+" to "+clock_out+"!", (clock_in), clock_out);
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
                            @if(count($claim->detail)!=0)
                                for(n=0; n<{{$no}}+1; n++){
                                    time = timemaster($('#oldds-'+n).text(), $('#oldde-'+n).text());
                                    if(check){
                                        if(start > time[0] && start < time[1]){
                                            if(i!=0){
                                                if(!($('#oldds-'+n).text()==start_time)){
                                                    check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                    calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());   
                                                }
                                            }
                                            else if(n!=0){
                                                check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);  
                                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());  
                                            }
                                        }
                                    }
                                }
                                @endif
                        @endif
                        if(check){
                            if(start > nstart && start < nend){
                                check = killview(i, "Time input cannot be between {{$day[0]}} and {{$day[1]}}!", start_time, end_time);
                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }
                        }
                        if($("#inputstart-"+i).val()!="" && $("#inputend-"+i).val()!=""){
                            // alert(start+" "+end);
                            if(start<end){
                                @if($claim ?? '')
                                    @if(count($claim->detail)!=0)
                                        for(n=0; n<{{$no}}+1; n++){
                                            time = timemaster($('#oldds-'+n).text(), $('#oldde-'+n).text());
                                            if(check){
                                                if((time[0]<end&&time[1]>start)){
                                                    if(i!=0){
                                                        if(!($('#oldds-'+n).text()==start_time)){
                                                            if(n!=i){
                                                                check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                                            }
                                                        }
                                                    }
                                                    else if(n!=0){
                                                        check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                        calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                                    }
                                                }
                                            }
                                        }
                                    @endif
                                @endif
                                if(check){
                                    if((end>nstart && end<nend)||(nstart<end && nend>start)){
                                        check = killview(i, "Time input cannot be between {{$day[0]}} and {{$day[1]}}!");
                                        calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                    }else{
                                        calshowtime(i, end-start, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                        // if(i!=0){
                                            $('#oldds-'+i).text($("#inputstart-"+i).val());
                                            $('#oldde-'+i).text($("#inputend-"+i).val());
                                    }
                                }
                            }else{
                                if(check){
                                    // alert("End time must be more than "+sh+":"+sm+me+"!");
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Input time error',
                                        text: "End time must be more than "+sh+":"+sm+"!"
                                        // text: "End time must be more than "+sh+":"+sm+me+"!"
                                    })
                                    // if(i==0){
                                    $("#inputend-"+i).val("");
                                    // }else{
                                    //     $("#inputend-"+i).val(clock_out);
                                    // }
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
                    $('#inputcheckdata-'+i).val("Y");
                    calshowtime(i, (parseInt($("#olddh-"+i).text()*60)+parseInt($("#olddm-"+i).text())), 0, 0, $("#oldth").text(), $("#oldtm").text());
                }else{
                    // $('#inputjustification').val("N");
                    $('#inputcheckdata-'+i).val("N");
                    nhm = showtime((parseInt($("#oldth").text()*60)+parseInt($("#oldtm").text()))-(parseInt($("#olddh-"+i).text()*60)+parseInt($("#olddm-"+i).text())));
                    $("#oldth").text(nhm[0]);
                    $("#oldtm").text(nhm[1]);
                    $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
                }
            }
        }

        function deleteid(i){
            return function(){
                var id = $("#delete-"+i).data('id');
                var ss = $("#delete-"+i).data('start');
                var ee = $("#delete-"+i).data('end');
                $("#delid").val(id);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete input time range from "+ss+" to "+ee+"?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete'
                    }).then((result) => {
                    if (result.value) {
                        $("#delete").submit();
                    }
                })
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
            $("#delete-"+i).on('click', deleteid(i));
        };
        
        //when click add time
        $("#add").on('click', function(){
            if(add){
                // $('#oldds-0').text($("#inputstart-0").val());
                // $('#oldde-0').text($("#inputend-0").val());
                $('#addform').css("display", "table-row");
                $("#inputstart-0").prop('required',true);
                $("#inputend-0").prop('required',true);
                $("#inputremark-0").prop('required',true);
                // calshowtime(0, (parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())), 0, 0, $("#oldth").text(), $("#oldtm").text());
                $('#nodata').css("display","none");
                add=false;  
            }else{
                // alert("Please save current time input before adding a new one!");
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to add time',
                    text: 'Please save current time input before adding a new one!'
                })
            }
        });
        
        //when cancel add time
        $("#cancel").on('click', function(){
            if(!(add)){
                $('#oldds-0').text("0");
                $('#oldde-0').text("0");
                $('#addform').css("display", "none");
                $("#inputduration-0").val('');
                $("#inputstart-0").val('');
                $("#inputend-0").val('');
                $("#inputstart-0").prop('required',false);
                $("#inputend-0").prop('required',false);
                $("#inputremark-0").prop('required',false);
                nhm = showtime((parseInt($("#oldth").text()*60)+parseInt($("#oldtm").text()))-(parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())));
                $("#oldth").text(nhm[0]);
                $("#oldtm").text(nhm[1]);
                $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
                $('#nodata').css("display","table-row");
                add=true;  
            }
        });

        $("#inputfile").on("change", function(){
            var filesize = this.files[0].size;
            if (filesize > 1000000) { 
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to upload file',
                    text: 'Uploaded file size has exceeded 1MB!'
                })
                $("#inputfile").val("");
            }else{
                $("#formsave").val("save");
                $("#formsubmit").val("no");
                $("#form").submit();
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
            $("#formadd").val("add");
            $("#formsubmit").val("no");
            $("#form").submit();
        }
    });     
    //when adding new time
    $("#btn-save").on('click', function(){
        if(add){
            $("#formsave").val("save");
            $("#formsubmit").val("no");
            $("#form").submit();
        }else{
            // alert("Please save new time input before saving the form!");
            Swal.fire({
                icon: 'error',
                title: 'Unable to save form',
                text: 'Please save new time input before saving the form!'
            })
        }
    });  
    
    function submission(){
        if(($("#formadd").val()=="no")&&($("#formsave").val()=="no")){
            $("#inputstart-0").prop('required',false);
            $("#inputend-0").prop('required',false);
            $("#inputremark-0").prop('required',false);
            if(@if($claim ?? ''){{count($claim->detail)}}@else 0 @endif!=0){
                if(whensubmit){
                    if(add){
                        Swal.fire({
                            title: 'Are you sure to submit form?',
                            text: "I understand and agree this to claim. If deemed false I can be taken to disciplinary action.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'I understand'
                            }).then((result) => {
                            if (result.value) {
                                whensubmit = false;
                                $("#form").submit();
                            }
                        })
                        return false;
                    }else{
                        // alert("Please save new time input before saving the form!");
                        Swal.fire({
                            icon: 'error',
                            title: 'Unable to submit form',
                            text: 'Please save new time input before submitting the form!'
                        })
                        $("#inputstart-0").prop('required',true);
                        $("#inputend-0").prop('required',true);
                        $("#inputremark-0").prop('required',true);
                        return false;
                    }
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to submit form',
                    text: 'Please add some claim time before submitting the form!'
                })
                $("#inputstart-0").prop('required',true);
                $("#inputend-0").prop('required',true);
                $("#inputremark-0").prop('required',true);
                return false;
            }
        }
    }

    $("#chargetype").change(function(){
        // alert()
        if($("#chargetype").val()=="Cost Center"){
            $("#costcenter").css("display", "block");
            $("#project").css("display", "none");
        }else if($("#chargetype").val()=="Project"){
            $("#project").css("display", "block");
            $("#costcenter").css("display", "none");
        }
    });    
</script>
@stop
           