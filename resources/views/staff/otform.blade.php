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
                    <p>Date: <input type="date" id="inputdate" name="inputdate" value=
                        @if($claim ?? '')
                            "{{$claim->date}}""
                        @elseif($draft ?? '')
                            "{{date('Y-m-d', strtotime($draft[6]))}}""
                        @endif" required>
                    </p>
                </form>
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
                @if($claim ?? '')
                    @if(($claim->status=="D1")||($claim->status=="D2"))
                        @php($c = true)
                    @elseif(($claim->status=="Q1")||($claim->status=="Q2"))
                        @php($q = true)
                    @endif
                @elseif($draft ?? '')
                    @php($d = true)
                @endif
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
                        @if(($claim->status=="Q2")||($claim->status=="Q1"))
                            {{str_replace('"', '', str_replace('Queried with message: "', '', $query))}}@endif</p>
                @endif
            </div>
            <div class="col-xs-6">
                <p>Status: 
                    @if($claim ?? '')  
                        @if(($claim->status=="D2")||($claim->status=="D1"))
                            Draft 
                        @elseif (($claim->status=="Q2")||($claim->status=="Q1"))
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
                @if(($c ?? '')||($d ?? ''))
                <div class="text-right" >
                    <button type="button" class="btn btn-primary" id="otedit">
                        ADD TIME
                    </button>
                    <p>Total time: {{$claim->time->hour}}h {{$claim->time->minute}}m / 104h</p>
                </div>
                @endif
            </div>
        </div>
        <form id="formsave" action="{{route('ot.formsave')}}" method="POST">
            <table class="table table-bordered">
                <thead>    
                    <tr class="info">
                        @if(($c ?? '')||($d ?? ''))
                            <th width="2%"></th>
                        @endif
                            <th width="2%">No</th>
                            <th width="20%">Clock In/Out</th>
                            <th width="20%">Start/End Time</th>
                            <th width="8%">Total Time</th>
                            <th width="38%">Justification</th>
                        @if(($c ?? '')||($d ?? ''))
                            <th>
                                Action
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($claim->detail as $no=>$singleuser)
                    <tr>
                        @php($s = false)
                        @if(($c ?? '')||($d ?? ''))
                            @php($s = true)
                        @else
                            @if($singleuser->checked=="X")
                                @php($s = true)
                            @endif
                        @endif
                        @if($s)
                            @if(($c ?? '')||($d ?? ''))
                                <td><input type="checkbox" id="inputcheck" name="inputcheck[]" value="{{$singleuser->id}}"
                                    @if($singleuser->checked=="X")
                                        checked
                                    @endif >
                                </td>
                            @endif
                            <td>{{++$no}}</td>
                            <td>
                                @if($singleuser->clock_in!="")
                                    {{date('H:i', strtotime($singleuser->clock_in))}} - {{date('H:i', strtotime($singleuser->clock_out))}} 
                                @else 
                                    Manual 
                                @endif
                            </td>
                            <td>
                                @if(($c ?? '')||($d ?? ''))
                                    {{--<!-- <input type="time" id="inputstart-{{$no}}" name="inputstart[]" value="{{ date('H:i', strtotime($singleuser->start_time))}}">
                                    <input type="time" id="inputend-{{$no}}" name="inputend[]" value="{{ date('H:i', strtotime($singleuser->end_time)) }}"> --> --}}
                                    <div class="input-group bootstrap-timepicker timepicker">
                                        <input id="timepicker1" type="text" class="form-control input-small"></span>
                                    </div>
                                @else
                                    {{ date('H:i', strtotime($singleuser->start_time)) }} - {{ date('H:i', strtotime($singleuser->end_time)) }}
                                @endif    
                            </td>
                            <td><span id="inputduration-{{$no}}">{{ $singleuser->hour }}h {{ $singleuser->minute }}m</span></td>
                            <td>
                                @if(($c ?? '')||($d ?? ''))
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
                            @if(($c ?? '')||($d ?? ''))
                                <td>
                                    @if($singleuser->clock_in=="")
                                        <button type="button" class="btn btn-danger" id="otx-{{$no}}" data-id="{{$singlevalue->id}}"><i class="fas fa-times"></i></button>
                                    @endif
                                </td>
                            @endif
                        @endif
                    </tr>
                @endforeach
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
    $("#inputdate").change(function(){
        alert($("#inputdate").val());
    });

    $('#timepicker1').timepicker({
        minuteStep: 1,
        showMeridian: false,
        defaultTime: false
    });
    
    $("#timepicker1").change(function(){
    });
    
</script>
@stop
           