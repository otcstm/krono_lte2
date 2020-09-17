@extends('adminlte::page')

@section('title', 'Overtime Form')

@section('content')
<style>
    .form-select{
        min-width: 40%;
    }
</style>

<h1>Apply New Overtime</h1>
@if($claim ?? '')
    @if(($claim->status=="D1")||($claim->status=="D2"))
        @php($c = true)
    @elseif(($claim->status=="Q1")||($claim->status=="Q2"))
        @php($q = true)
    @endif
@elseif($draft ?? '')
    @php($d = true)
@else
@endif



<div class="panel panel-default panel-main">
    <div class="panel panel-default">
        <div class="panel-heading panel-primary">Overtime Application (Status: 
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
                                Approved 
                            @else 
                                {{ $claim->status }} 
                            @endif  
                        @elseif($draft ?? '') 
                            Draft 
                        @else 
                            N/A
                        @endif)
                    </div>
        <div class="panel-body" style="min-height:50vh">
            {{--@if(session()->has('feedback'))
            <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{session()->get('feedback_text')}}
            </div>
            @endif--}}
            <div class="row">
                <div class="col-md-6">
                    
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
                        <p>State Calendar: 
                            @if($claim ?? '') 
                                {{str_replace(')', '', str_replace('Malaysia (', '', $claim->state->state_descr))}}
                            @elseif($draft ?? '') 
                                {{str_replace(')', '', str_replace('Malaysia (', '', $draft[7]))}}
                            @else 
                                {{str_replace(')', '', str_replace('Malaysia (', '', Auth::user()->stateid->state_descr))}} {{--draft{{Auth::user()->stateid->state_descr}}--}}
                            @endif
                        </p>
                        <form id="formdate" action="{{route('ot.formdate')}}" method="POST">
                            @csrf
                            <p>OT Date: <input type="text" data-language='en' data-date-format="dd.mm.yyyy" id="inputdate" name="inputdate" 
                                style ="width: 100px"
                                @if($claim ?? '')
                                    value="{{date('d.m.Y', strtotime($claim->date))}}"
                                @elseif($draft ?? '')
                                    value="{{date('d.m.Y', strtotime($draft[4]))}}"
                                @else
                                    value=""
                                @endif
                                required  onkeydown="return false">
                                @if($claim ?? '')
                                    ({{date('l', strtotime($claim->date))}})
                                @elseif($draft ?? '')
                                    ({{date('l', strtotime($draft[4]))}})
                                @endif
                                <!-- <button type="button" id="btn-date" class="btn btn-primary" style="padding: 2px 3px; margin: 0; margin-top: -3px;"><i class="fas fa-share-square"></i></button> -->
                            </p>
                        </form>    
                        <p>Day Type:
                            @if($claim ?? '')  
                                {{--$claim->daytype->description--}}
                                @if($claim->daytype->day_type == "N")
                                    Normal Day
                                @elseif($claim->daytype->day_type == "PH")
                                    Public Holiday
                                @elseif($claim->daytype->day_type == "R")
                                    Rest Day
                                @else
                                    Off Day
                                @endif
                            @elseif(($draft ?? ''))
                                {{--$draft[8]--}}
                                @if($draft[8] == "N")
                                    Normal Day
                                @elseif($draft[8] == "PH")
                                    Public Holiday
                                @elseif($draft[8] == "R")
                                    Rest Day
                                @else
                                    Off Day
                                @endif
                            @else 
                                N/A
                            @endif</p>   
                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                            @php($expiry = true)
                            @if(($claim ?? ''))
                                @if($claim->date_expiry==null)
                                    @php($expiry = false)
                                @endif
                            @elseif(($draft ?? ''))
                                @if($draft[1]==null)
                                    @php($expiry = false)
                                @endif
                            @endif
                            @if(!(($claim ?? '')||($draft ?? '')))
                                <p>Charging type: {{$claim->charge_type}}</p>
                            @endif
                        @endif
                    
                    
                    </div>
                </div>
                <div class="col-md-6">
                    <p>Work Schedule: 
                        @if($claim ?? '') 
                            {{$claim->employee_type}} 
                        @elseif($draft ?? '') 
                            {{$draft[14]}} 
                        @else 
                            N/A
                        @endif
                    </p>
                    <p>Salary Capping for OT: 
                        @if($claim ?? '') 
                            {{$claim->salary_exception}} 
                        @elseif($draft ?? '') 
                            {{$draft[15]}} 
                        @else 
                            N/A
                        @endif
                    </p>
                    <p>Verifier: 
                        @if($claim ?? '') 
                            {{$claim->verifier->name}} @if($claim->verifier->name!="N/A") ({{$claim->verifier->staff_no}}) @endif 
                        @elseif($draft ?? '')
                            {{$draft[9]}} @if($draft[9]!="N/A") ({{$draft[12]}}) @endif
                        @else 
                            N/A 
                        @endif
                    </p>
                    <p>Approver: 
                        @if($claim ?? '')
                            {{$claim->approver->name}} ({{$claim->approver->staff_no}})
                        @elseif($draft ?? '')
                            {{$draft[10]}} ({{$draft[13]}})
                        @else 
                            N/A
                        @endif
                    </p>
                    @if($q ?? '')
                        <p>Query Message: 
                            @foreach($claim->log as $logs) 
                                @if(strpos($logs->message,"Queried")!==false) 
                                    @php($query = $logs->message) 
                                @endif 
                            @endforeach 
                            {{str_replace('"', '', str_replace('Queried with message: "', '', $query))}}</p>
                    @endif
                    {{--<!-- <p>Estimated Amount: RM
                        @if($claim ?? '') 
                            {{$claim->amount}} 
                        @else 
                            0.00
                        @endif</p> -->--}}
                </div>
                <div class="col-md-12">
                 @if(($c ?? '')||($d ?? '')||($q ?? ''))
                    {{--@if(($claim ?? '')||($draft ?? ''))--}}
                        @if($expiry)
                        <span style="color: red">
                            <p>Submission Due Date: 
                                @if($claim ?? '') 
                                    {{date("d.m.Y", strtotime($claim->date_expiry))}}
                                @else 
                                    {{date("d.m.Y", strtotime($draft[1]))}} 
                                @endif Unsubmitted claims will be deleted after the due date</p>
                        </span>
                        @endif
                    {{--@else--}}
                        <!-- <p>Charging type: {{--$claim->charge_type--}}</p> -->
                    {{--@endif--}}
                @endif
                </div>
            </div>
            <div class="row" style="display: flex; margin-top: 50px">
                <div class="col-xs-6">
                    <p><b>TIME LIST</b> </p>
                    @if(($c ?? '')||($d ?? '')) 
                        <p>
                            <b>Applicable Time Range:</b>
                            {{$start}} 
                            @if($c ?? '')  
                                <b>({{date('d.m.Y', strtotime($claim->date))}})</b>
                            @elseif($d ?? '')  
                                <b>({{date("d.m.Y", strtotime($draft[4]))}})</b>
                            @endif
                                -  {{$end}}
                            @if($c ?? '') 
                                @if($shift == "Yes") 
                                    <b>({{date('d.m.Y', strtotime($claim->date. ' + 1 days'))}})</b>
                                @else 
                                    <b>({{date('d.m.Y', strtotime($claim->date))}})</b>
                                @endif
                            @elseif($d ?? '')
                                @if($shift == "Yes") 
                                    <b>({{date('d.m.Y', strtotime($draft[4]. ' + 1 days'))}})</b>
                                @else
                                    <b>({{date("d.m.Y", strtotime($draft[4]))}})</b>
                                @endif
                            @endif
                                    
                            </span>
                        </p>
                        <p> <span style="color: red">
                        @php($show = true)
                        @if($c ?? '')  
                            @if($claim->daytype->day_type != "N")
                                @php($show = false)
                            @endif
                        @elseif($d ?? '')
                            @if($draft[8] != "N")
                                @php($show = false)
                            @endif
                        @endif
                        @if($show)

                            <b>Working Time Range:</b> {{$day[0]}} 
                                @if($c ?? '')  
                                <b>({{date('d.m.Y', strtotime($claim->date))}})</b>
                                @elseif($d ?? '')  
                                <b>({{date("d.m.Y", strtotime($draft[4]))}})</b>
                                @endif
                                - {{$day[1]}}
                                @if($day[6])
                                    @if($c ?? '')  
                                    <b>({{date('d.m.Y', strtotime($claim->date))}})</b>
                                    @elseif($d ?? '')  
                                    <b>({{date("d.m.Y", strtotime($draft[4]))}})</b>
                                    @endif
                                @else
                                    @if($c ?? '')  
                                    <b>({{date('d.m.Y', strtotime($claim->date. ' + 1 days'))}})</b>
                                    @elseif($d ?? '')  
                                    <b>({{date('d.m.Y', strtotime($draft[4]. ' + 1 days'))}})</b> 
                                    @endif
                                @endif
                            </span></p>
                            
                        @endif
                    @endif
                </div>
                <div class="col-xs-6" style="display: flex; flex-direction: row; justify-content: flex-end; align-items: flex-end">
                    @if($claim ?? '')
                        @if(in_array($claim->status, $array = array("D1", "D2", "Q1", "Q2")))
                            @php($c = true)
                        @endif
                    @endif
                    @if(($c ?? '')||($d ?? '')||($q ?? ''))
                    <div class="text-right" >
                        <button type="button" class="btn-up" id="add" style="margin-bottom: 5px;">
                        <i class="fas fa-plus-circle"></i> ADD TIME
                        </button>
                        <p>Total time: 
                                @if($claim ?? "")
                                    <span id="oldth" class="hidden">{{$claim->time->hour}}</span>
                                    <span id="oldtm" class="hidden">{{$claim->time->minute}}</span>
                                    <span id="showtime">
                                        {{$claim->time->hour}}h {{$claim->time->minute}}m
                                    </span>
                                @else
                                    <span id="oldth" class="hidden">{{$draft[3]->hour}}</span>
                                    <span id="oldtm" class="hidden">{{$draft[3]->minute}}</span>
                                    <span id="showtime">
                                        {{$draft[3]->hour}}h {{$draft[3]->minute}}m
                                    </span>
                                @endif
                            / {{$eligiblehour}}h
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            <form id="form" action="{{route('ot.formsubmit')}}" method="POST" onsubmit="return submission()" enctype="multipart/form-data">
                @csrf
                <input type="text" class="form-control hidden" id="inputid" name="inputid" value="@if($claim ?? '') {{$claim->id}} @endif">
                <input type="text" class="hidden" id="inputdates" name="inputdates">
                <input type="text" class="hidden" id="usertype" name="usertype" 
                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                    @if($shift=="Yes")
                        value="Shift"
                    @else
                        value="Normal"
                    @endif
                @endif
                >
                <input class="hidden" id="formtype" type="text" name="formtype" value="submit">
                <input class="hidden" id="filedel" type="text" name="filedel" value="">
                <!-- <input class="hidden" id="formadd" type="text" name="formadd" value="no">
                <input class="hidden" id="formsave" type="text" name="formsave" value="no">
                <input class="hidden" id="formsubmit" type="text" name="formsubmit" value="yes"> -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>    
                            <tr>
                                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                    <th width="2%"></th>
                                @endif
                                    <th width="2%">No</th>
                                    <!-- <th width="20%">Clock In/Out</th> -->
                                    
                                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                    @if($shift=="Yes")
                                        <th>Date</th>
                                    @endif
                                @endif
                                    <th>Start OT</th>
                                    <th>End OT</th>
                                    <th>Hours/Minutes</th>
                                    <th>Input Type</th>
                                    <th>Location</th>
                                    <th>OT Remark</th>
                                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                    <th width="6%">
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
                                                    <td><input type="checkbox" id="inputcheck-{{++$no}}" class="check-{{$no}}-0"
                                                        @if($singleuser->checked=="Y")
                                                            checked
                                                        @endif >
                                                        <input type="text" class="hidden" id="inputcheckdata-{{$no}}" name="inputcheck[]" value="{{$singleuser->checked}}">
                                                    </td>
                                                @else
                                                    @php(++$no)
                                                @endif
                                                <td>{{$no}}</td>
                                                {{-- <!-- <td>
                                                    @if($singleuser->clock_in!="")
                                                        {{date('Hi', strtotime($singleuser->clock_in))}} - {{date('Hi', strtotime($singleuser->clock_out))}} 
                                                    @else 
                                                        Manual Input
                                                    @endif
                                                </td> --> --}}
                                                @if($shift=="Yes")
                                                    <td>
                                                       {{date('d.m.Y', strtotime($singleuser->start_time))}}
                                                       <input class="hidden" id="inputdate-{{$no}}" value="{{date('d.m.Y', strtotime($singleuser->start_time))}}">
                                                    </td>
                                                @endif
                                                <td>
                                                    @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                                        <span id="oldds-{{$no}}" class="hidden">{{date('H:i', strtotime($singleuser->start_time))}}</span>
                                                        <input style="width: 50px" id="inputstart-{{$no}}" name="inputstart[]" type="text" class=" check-{{$no}} check-{{$no}}-1 @if($singleuser->checked=="N") hidden @endif" 
                                                            data-clock_in="{{ date('H:i', strtotime($singleuser->clock_in))}}"
                                                            data-start_time="{{ date('H:i', strtotime($singleuser->start_time))}}"
                                                            @if($singleuser->clock_in!="")
                                                                data-clocker="{{ date('H:i', strtotime($singleuser->start_time))}}"
                                                            @endif
                                                            value="{{ date('H:i', strtotime($singleuser->start_time))}}" required>
                                                        @if($singleuser->checked=="N")
                                                            {{ date('Hi', strtotime($singleuser->start_time)) }}
                                                        @endif
                                                    @else
                                                        {{ date('Hi', strtotime($singleuser->start_time)) }}
                                                    @endif    
                                                </td>
                                                <td>
                                                    @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                                        <span id="oldde-{{$no}}" class="hidden">@if(date('H:i', strtotime($singleuser->end_time))=='00:00')24:00 @else{{date('H:i', strtotime($singleuser->end_time))}} @endif</span>
                                                        <input style="width: 50px" id="inputend-{{$no}}" name="inputend[]" type="text" class=" check-{{$no}} check-{{$no}}-2 @if($singleuser->checked=="N") hidden @endif" 
                                                            data-clock_out="{{ date('H:i', strtotime($singleuser->clock_out))}}"
                                                            data-end_time="@if(date('H:i', strtotime($singleuser->end_time))=='00:00')24:00 @else{{date('H:i', strtotime($singleuser->end_time))}} @endif"
                                                            value="@if(date('H:i', strtotime($singleuser->end_time))=='00:00')24:00 @else{{date('H:i', strtotime($singleuser->end_time))}} @endif" required>
                                                        @if($singleuser->checked=="N")
                                                            @if(date('H:i', strtotime($singleuser->end_time))=="00:00")
                                                                24:00
                                                            @else
                                                                {{ date('H:i', strtotime($singleuser->end_time)) }}
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{ date('Hi', strtotime($singleuser->end_time)) }}
                                                    @endif    
                                                </td>
                                                <td>
                                                    <span id="fixdh-{{$no}}" class="hidden">{{$singleuser->hour}}</span>
                                                    <span id="fixdm-{{$no}}" class="hidden">{{$singleuser->minute}}</span>
                                                    <span id="olddh-{{$no}}" class="hidden">{{$singleuser->hour}}</span>
                                                    <span id="olddm-{{$no}}" class="hidden">{{$singleuser->minute}}</span>
                                                    <span id="inputduration-{{$no}}">{{ $singleuser->hour }}h {{$singleuser->minute}}m</span>
                                                </td>
                                                <td>
                                                    @if($singleuser->clock_in!="")
                                                        Auto
                                                    @else 
                                                        Manual
                                                    @endif
                                                </td>
                                                <td>@if($singleuser->clock_in=="") - @else <a href = "https://www.google.com/maps/search/?api=1&query={{$singleuser->in_latitude}},{{$singleuser->in_longitude}}" target="_blank" style="font-weight: bold; color: #143A8C">{{ $singleuser->in_latitude }} {{ $singleuser->in_longitude }}</a> @endif</td>
                                                <td>
                                                    @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                                        <textarea rows = "1" cols = "60" type="text" id="inputremark-{{$no}}" name="inputremark[]" placeholder="Input remark" class="check-{{$no}} check-{{$no}}-3 @if($singleuser->checked=="N") hidden @endif" style="resize: none" @if($singleuser->checked=="Y") required  @endif>{{$singleuser->justification}}</textarea>
                                                        @if($singleuser->checked=="N")
                                                            {{$singleuser->justification}}
                                                        @endif
                                                    @else
                                                        {{$singleuser->justification}}
                                                    @endif 
                                                </td>
                                                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                                    <td>
                                                        @if($singleuser->clock_in=="")
                                                            <button type="button" class="btn btn-np" id="delete-{{$no}}" data-id="{{$singleuser->id}}" data-start="{{date('H:i', strtotime($singleuser->start_time))}}" data-end="{{date('H:i', strtotime($singleuser->end_time))}}"><i class="fas fa-trash-alt"></i></button>
                                                        @endif
                                                    </td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr id="nodata" class="text-center"><td
                                        @if($shift=="Yes")
                                            colspan="10"
                                        @else
                                            colspan="9"
                                        @endif
                                    ><i>Not Available</i></td></tr>
                                @endif
                            @else
                                <tr id="nodata" class="text-center"><td 
                                
                                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                    @if($shift=="Yes")
                                            colspan="10"
                                        @else
                                            colspan="9"
                                    @endif
                                @else
                                colspan="9"
                                @endif
                                ><i>Not Available</i></td></tr>
                            @endif
                            <tr id="addform" style="display: none">
                                <td></td>
                                <td>@if($claim ?? '') {{count($claim->detail)+1}} @else 1 @endif</td>
                                <!-- <td>Manual Input</td> -->
                                
                                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                    @if($shift=="Yes")
                                        <td>
                                            <select name="inputdatenew" id="inputdate-0" class=" check-0 check-0-3"> 
                                            
                                                <option value="" selected hidden>Select date</option>
                                                <option  
                                                    @if($claim ?? '')
                                                        value="{{date('d.m.Y', strtotime($claim->date))}}"
                                                    @elseif($draft ?? '')
                                                        value="{{date('d.m.Y', strtotime($draft[4]))}}"
                                                    @endif
                                                >
                                                @if($claim ?? '')
                                                    {{date('d.m.Y', strtotime($claim->date))}} 
                                                @elseif($draft ?? '')
                                                    {{date('d.m.Y', strtotime($draft[4]))}}
                                                @endif
                                                </option>
                                                <option 
                                                    @if($claim ?? '')
                                                        value="{{date('d.m.Y', strtotime($claim->date."+1 day"))}}"
                                                    @elseif($draft ?? '')
                                                        value="{{date('d.m.Y', strtotime($draft[4]."+1 day"))}}"
                                                    @endif
                                                >
                                                    @if($claim ?? '')
                                                        {{date('d.m.Y', strtotime($claim->date."+1 day"))}}
                                                    @elseif($draft ?? '')
                                                        {{date('d.m.Y', strtotime($draft[4]."+1 day"))}}
                                                    @endif
                                                </option>
                                            </select>
                                        
                                        </td>
                                    @endif
                                @endif
                                <td>
                                    <span id="oldds-0" class="hidden">0</span>
                                    <!-- <input style="width: 40px" id="inputstart-0" type="text" name="inputstartnew" class="timepicker check-0 check-0-0"> -->
                                    <input style="width: 50px" id="inputstart-0" type="text" name="inputstartnew" class=" check-0 check-0-0"
                                        
                                        @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                            @if($shift=="Yes")
                                                disabled
                                            @endif
                                        @endif
                                    >
                                </td>
                                <td>
                                    <span id="oldde-0" class="hidden">0</span>
                                    <input style="width: 50px" id="inputend-0" type="text" name="inputendnew" class=" check-0 check-0-1" disabled>
                                </td>
                                <td>
                                    <span id="olddh-0" class="hidden">0</span>
                                    <span id="olddm-0" class="hidden">0</span>
                                    <span id="inputduration-0"></span>
                                </td>
                                <td>Manual</td>
                                <td>-</td>
                                <td><textarea rows = "1" cols = "60" type="text"  id="inputremark-0" name="inputremarknew" placeholder="Input remark" style="resize: none" class="check-0 check-0-2"></textarea></td>
                                <td>
                                    <!-- <button type="button" class="btn btn-primary" id="btn-add"><i class="fas fa-save"></i></button> -->
                                    <button type="button" class="btn btn-np" id="cancel" style="display: inline"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        </tbody>  
                    </table>
                </div>
                @if(($c ?? '')||($d ?? '')||($q ?? ''))
                    <!-- <div style="margin-top: -15px"><small>
                        <p>* Accepted format JPG, JPEG, PNG, BMP & PDF only
                        <br>* Maximum size of supporting document is 1MB
                        <br>* Make sure your PDF document is <u>not password protected</u> and <u>not corrupted</u> </p>
                    </small></div> -->
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                
                                <div class="row" style="margin-bottom: 5px;">
                                    <div class="col-md-3">
                                        <label>Charge Type:</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="chargetype" class="chargetype form-select" id="chargetype" value="
                                            @if($claim ?? '')
                                                {{$claim->charge_type}}
                                            @endif" required>
                                            <option value="Own Cost Center" 
                                                @if($claim ?? '') 
                                                    @if($claim->charge_type=="Own Cost Center")
                                                        selected
                                                    @endif 
                                                @else 
                                                    selected
                                                @endif>OWN COST CENTER</option>
                                            <option value="Project" 
                                                @if($claim ?? '') 
                                                    @if($claim->charge_type=="Project") 
                                                        selected 
                                                    @endif 
                                                @endif>
                                                PROJECT
                                            </option>
                                            <option value="Internal Order" 
                                                @if($claim ?? '') 
                                                    @if($claim->charge_type=="Internal Order") 
                                                        selected 
                                                    @endif 
                                                @endif>
                                                INTERNAL ORDER
                                            </option>
                                            <option value="Maintenance Order" 
                                                @if($claim ?? '') 
                                                    @if($claim->charge_type=="Maintenance Order") 
                                                        selected 
                                                    @endif 
                                                @endif>
                                                MAINTENANCE ORDER
                                            </option>
                                            <option value="Other Cost Center" 
                                                @if($claim ?? '') 
                                                    @if($claim->charge_type=="Other Cost Center") 
                                                        selected 
                                                    @endif 
                                                @endif>
                                                OTHER COST CENTER
                                            </option>
                                        </select> 
                                    </div>
                                </div>

                                @if($claim ?? '')
                                    <!-- order no-->
                                    <div
                                        @if(!(in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order"))))
                                            style="display: none"
                                        @endif
                                    >
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-md-3">
                                                <label>No:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-select" style="position: relative; z-index: 8;" id="orderno" name="orderno" placeholder="@if($claim->charge_type=="Project") Search project no" @if($claim->project_no!=null) value="{{$claim->project_no}}"@endif @else Search order no" @if($claim->order_no!=null) value="{{$claim->order_no}}" @endif @endif @if(in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order"))) required @endif data-readonly >
                                                <i id="ordernosearch" style="position: relative; z-index: 9; margin-left: -25px" class="fas fa-search"></i>
                                                <!-- <div class="aaaa" style="position: fixed;   z-index: 9999; width: 100%; border: red;">a</div> -->
                                                {{-- <!-- <select class="form-select" name="orderno" id="orderno" required 
                                                @if($claim->charge_type=="Project") 
                                                    @if($orderlist==null) disabled @endif
                                                @else
                                                    @if($orderno==null) disabled @endif
                                                @endif>
                                                    <option value="" @if($claim->project_type==NULL) selected @endif hidden>Select @if($claim->charge_type=="Project") project @else order @endif no</option>
                                                    @if($claim->charge_type=="Project") 
                                                        @if($orderlist!=null)
                                                            @foreach($orderlist as $singleorder)
                                                                <option value="{{$singleorder->project_no}}" @if($claim->project_no==$singleorder->project_no) selected @endif>{{$singleorder->project_no}}</option>
                                                            @endforeach
                                                        @endif
                                                    @else
                                                        @if($orderno!=null)
                                                            @foreach($orderno as $singleorder)
                                                                <option value="{{$singleorder->id}}" @if($claim->order_no==$singleorder->id) selected @endif>{{$singleorder->id}}</option>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </select>  --> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- descr-->
                                    <div
                                        @if(!(in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order"))))
                                            style="display: none"
                                        @endif
                                    >
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-md-3">
                                                <label>Description:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-select" @if($data!=null) value="{{$data->descr}}"  @endif disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- network header-->
                                    <div
                                    @if(!($claim->charge_type=="Project"))
                                        style="display: none"
                                    @endif
                                    >
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-md-3">
                                                <label>Network Header:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-select" @if($data!=null) value="{{$data->network_header}}"  @endif disabled>
                                                <input type="text" class="hidden" name="networkh" @if($data!=null) value="{{$data->network_header}}"  @endif readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- network header desc-->
                                    <div
                                    @if(!($claim->charge_type=="Project"))
                                        style="display: none"
                                    @endif
                                    >
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-md-3">
                                                <label>Network Header Description:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-select" @if($data!=null) value="{{$data->network_headerdescr}}"  @endif disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- network activity no-->
                                    <div
                                    @if(!($claim->charge_type=="Project"))
                                        style="display: none"
                                    @endif
                                    >
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-md-3">
                                                <label>Network Activity No:</label>
                                            </div>
                                            <div class="col-md-9">
                                                    <select class="form-select" name="networkn" id="networkn" @if($claim->charge_type=="Project") @if($networkn!=null) required @endif @endif @if($networkn==null) disabled @endif>
                                                        <option value="" @if($claim->project_type==NULL) selected @endif hidden>Select network activity no</option>
                                                    @if($networkn!=null)
                                                        @foreach($networkn as $singlenet)
                                                            <option value="{{$singlenet->network_act_no}}" @if($claim->network_act_no==$singlenet->network_act_no) selected @endif>{{$singlenet->network_act_no}}</option>
                                                        @endforeach
                                                    @endif
                                                </select> 
                                            </div>
                                        </div>
                                    </div>

                                    <!-- network activity descr-->
                                    <div
                                    @if(!($claim->charge_type=="Project"))
                                        style="display: none"
                                    @endif
                                    >
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-md-3">
                                                <label>Network Activity Description:</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-select" @if($claim->network_act_no!=NULL) @if($data!=null) value="{{$data->network_act_descr}}" @endif  @endif disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- type-->
                                    <div
                                        @if(!(in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order"))))
                                            style="display: none"
                                        @endif
                                    >
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-md-3">
                                                <label>Type:</label>
                                            </div>
                                            <div class="col-md-9">
                                                @if(($claim->charge_type=="Project")||($claim->charge_type=="Maintenance Order")) 
                                                    <input type="text" name="type" class="form-select" @if($data!=null) value="{{$data->type}}" @endif disabled>
                                                @else
                                                    <input type="text" name="type" class="form-select" @if($data!=null) value="{{$data->order_type}}" @endif disabled>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- company code-->
                                <div
                                    @if($claim ?? '')                                        
                                        @if($claim->charge_type=="Own Cost Center")
                                            style="display: none" 
                                        @endif
                                    @elseif($draft ?? '')
                                        style="display: none" 
                                    @endif >
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="col-md-3">
                                            <label>Company Code:</label>
                                        </div>
                                        <div class="col-md-9">
                                            @if($claim ?? '')
                                                @if($claim->charge_type=="Other Cost Center")
                                                    <select class="form-select" name="compn" id="compn"required>
                                                        <option value="" @if($claim->company_id==NULL) selected @endif hidden>Select company code</option>
                                                        @if($compn!=null)
                                                            @foreach($compn as $singlecompn)
                                                                <option value="{{$singlecompn->company_id}}" @if($claim->company_id==$singlecompn->company_id) selected @endif>{{$singlecompn->company_id}} - {{$singlecompn->company->company_descr}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                @else
                                                    <input type="text" class="form-select" @if($data!=null)  value="{{$data->company_code}}" @endif disabled>
                                                    <input type="text" class="hidden" name="compn" @if($data!=null)  value="{{$data->company_code}}" @endif readonly>
                                                @endif
                                            @elseif($draft ?? '')
                                                <input type="text" class="form-select" value="Auth::user()->company_id">
                                            @endif 
                                        </div>
                                    </div>
                                </div>
                
                                <!-- cost center -->
                                <div>
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="col-md-3">
                                            <label>Cost Center:</label>
                                        </div>
                                        <div class="col-md-9">
                                            @if($claim ?? '')
                                                @if($claim->charge_type=="Own Cost Center") 
                                                    <input type="text" class="form-select"  value="{{$claim->costcenter}}" disabled>
                                                @elseif(in_array($claim->charge_type, $array = array("Internal Order","Other Cost Center")))
                                                    @if($costc!=null)
                                                        <select class="form-select" name="costc" id="costc" required @if($costc==null) disabled @endif>
                                                            <option value="" @if($claim->other_costcenter==NULL) selected @endif hidden>Select cost center</option>
                                                            @foreach($costc as $singlecostc)
                                                                <option value="{{$singlecostc->id}}" @if($claim->other_costcenter==$singlecostc->id) selected @endif>{{$singlecostc->id}}</option>
                                                            @endforeach
                                                        </select> 
                                                    @else
                                                        <input type="text" class="form-select"  @if($data!=null) value="{{$data->cost_center}}" @endif disabled>
                                                        <input type="text" class="hidden" name="costc"  @if($data!=null) value="{{$data->cost_center}}" @endif readonly >
                                                    @endif
                                                @else
                                                    <input type="text" class="form-select" @if($data!=null)  value="{{$data->cost_center}}" @endif disabled>
                                                    <input type="text" class="hidden" name="costc" @if($data!=null)  value="{{$data->cost_center}}" @endif readonly>
                                                @endif
                                            @elseif($draft ?? '')
                                                <input type="text" class="form-select" value="{{$draft[11]}}" disabled>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($claim ?? '')   
                                <!-- approver id-->
                                <div
                                    @if($claim->charge_type=="Own Cost Center")
                                        style="display: none"
                                    @endif
                                >
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="col-md-3">
                                            <label>Approver:</label>
                                        </div>
                                        <div class="col-md-9">
                                            @if(in_array($claim->charge_type, $array = array("Internal Order","Other Cost Center")))
                                                <select class="form-select" name="approvern" id="approvern" required @if($appr==null) disabled @endif>
                                                    <option value="" @if($claim->approver_id==NULL) selected @endif hidden>Select approver</option>
                                                    @if($appr!=null)
                                                        @foreach($appr as $singleappr)
                                                            <option value="{{$singleappr->user_id}}" @if($claim->approver_id==$singleappr->user_id) selected @endif>{{$singleappr->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select> 
                                            @else
                                                <input type="text" name="approvern" class="form-select" @if($data!=NULL) @if($data->name) value="{{$data->name->name}}" @endif @endif disabled>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                                <div class="row" style="margin-bottom: 5px;">
                                    <div class="col-md-3">
                                        <label>Document:</label>
                                    </div>
                                    <div class="col-md-9 maxfilef">
                                        <input type="file" name="inputfile" id="inputfile" accept="image/*, .pdf, .jpeg, .jpg, .bmp, .png, .tiff" style="position:absolute; right:-100vw;">
                                        <span id="inputfiletext" style="height: 25px; border-radius: 3px; border: 1px solid #707070;">File: .bmp, .pdf, .png, .jpg, .jpeg, .tiff</span>
                                        <a href="#" id="btn-file-2"><i class="fas fa-times-circle"></i></a>
                                        <button type="button" class="btn-up" id="btn-file-1" style="min-width: 80px">BROWSE</button>
                                        <button type="button" class="btn-up" id="btn-file-3" style="min-width: 80px; display: none;">UPLOAD</button>
                                        <span class="maxfile">Maximum file size: 1mb</span>
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9 col-md-offset-3">
                                    
                                        @if($claim ?? '')
                                            @foreach($claim->file as $f=>$singlefile)
                                                @php(++$f)
                                                <!-- <a href="{{-- asset('storage/'.$singlefile->filename)--}}" target="_blank"><img src="{{route('ot.thumbnail', ['tid'=>$singlefile->id], false)}}" title="{{ substr($singlefile->filename, 22)}}"  class="img-fluid img-thumbnails" style="height: 100px; width: 100px; border: 1px solid #A9A9A9; margin-bottom: 10px;"></a> -->
                                                <a href="{{route('ot.file', ['tid'=>$singlefile->id], false)}}" target="_blank"><img src="{{route('ot.thumbnail', ['tid'=>$singlefile->id], false)}}" title="{{ substr($singlefile->filename, 22)}}"  class="img-fluid img-thumbnails" style="height: 100px; width: 100px; border: 1px solid #A9A9A9; margin-right: 10px; margin-bottom: 10px;"></a>
                                                <a href="#" id="btn-file-del-{{$f}}" style="position: absolute; margin-left: -35px; top: 3px; color: red;" data-id="{{$singlefile->id}}" data-img="{{route('ot.thumbnail', ['tid'=>$singlefile->id], false)}}" data-name="{{substr($singlefile->filename, 22)}}"><i class="fas fa-times-circle"></i></a>

                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>   
                    @if((($c ?? '')||($d ?? '')||($q ?? '')))
                    <div class="panel-footer">
                        <div class="text-right">
                        <a href="{{route('ot.list')}}"><button type="button" class="btn btn-p btn-primary btn-outline" style="display: inline">BACK</button></a>
                            <!-- <button type="button" id="btn-save" class="btn btn-primary" style="display: inline"><i class="fas fa-save"></i> SAVE</button> -->
                            <button type="submit" id="sub" class="btn btn-p btn-primary">SUBMIT</button>
                        </div>
                    @endif
                </form>
                <form id="delete" class="hidden" action="{{route('ot.formdelete')}}" method="POST" onsubmit="return deletes()">
                    @csrf
                    <input type="text" id="delid" name="delid" value="">
                </form>
            </div>
                @endif
            @if(!(($c ?? '')||($d ?? '')||($q ?? '')))
            </div> 
            <div class="panel-footer">
                    <div class="text-right">
                        <a href="{{route('ot.list')}}"><button type="button" class="btn btn-p btn-primary" style="display: inline">BACK</button></a>
                    </div>
            </div>
            @endif
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
// alert($("#inputdates").val());
    @if(($claim ?? '')||($draft ?? ''))
        $("#inputdates").val($("#inputdate").val());
        // alert("test");
    @endif

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
    // var checker = true; //since bootstrap timepicker do onchange twice
    var submit = false; //check validation before adding new time
    var check=true;
    var whensubmit = true;
    //set min and max date
    var today = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var m = today.getMonth()+1;
    var y = today.getFullYear();
    var d = today.getDate().toString();
    var minm = today.getMonth()-1;
    if (minm==0){
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
    // $("#inputdate").attr("min", miny+"-"+minm+"-01");
    $("#inputdate").attr("max", y+"-"+m+"-"+d);

    var noloop = false;

    $('#inputdate').datepicker({
        language: 'en',
        maxDate: new Date(y+"-"+m+"-"+d),
        onSelect: function onSelect(){
            if(noloop){
                if($("#inputdate").val()!=""){
                    $("#formdate").submit();
                }
            }
        },
        selectDate: new Date(y+"-"+m+"-"+d),
            {{--@if($claim ?? '')
            selectDate : new Date("{{$claim->date}}"),
            @elseif($draft ?? '')
            selectDate : new Date("{{date('Y-m-d', strtotime($draft[4]))}}"),
            @endif--}}
        // maxDate:  y+"-"+m+"-"+d // Now can select only dates, which goes after today
    })

    // var defaultd =  $('#inputdate').datepicker().data('datepicker');

    if(!(noloop)){
    @if($claim ?? '')
        $('#inputdate').data('datepicker').selectDate(new Date("{{$claim->date}}"));
    
    @elseif($draft ?? '')

        $('#inputdate').data('datepicker').selectDate(new Date("{{date('Y-m-d', strtotime($draft[4]))}}"));
    @endif
        noloop = true;
    }

    // //when date input is changed
    $("#inputdate").change(function(){
    // $("#btn-date").on('click', function(){
        
        // if(
        //     ((Date.parse($("#inputdate").val()))<=Date.parse(monthNames[m-1]+" "+d+", "+y+" 23:59:59"))&&
        //     ((Date.parse($("#inputdate").val()))>=Date.parse(monthNames[minm-1]+" 01, "+miny+" 00:00:00"))
        //     ){
            $("#formdate").submit();
        // }
    });
        
    // $("#inputdate").change(function(){
    //     if(
    //         !(((Date.parse($("#inputdate").val()))<=Date.parse(monthNames[m-1]+" "+d+", "+y+" 23:59:59"))&&
    //         ((Date.parse($("#inputdate").val()))>=Date.parse(monthNames[minm-1]+" 01, "+miny+" 00:00:00")))
    //         ){
    //             Swal.fire(
    //                 'Invalid date input!',
    //                 "Claim date must be between 01-"+minm+"-"+miny+" and "+d+"-"+m+"-"+y+"!",
    //                 'error'
    //             )
    //             // alert("Claim date must be between "+miny+"-"+minm+"-01 and "+y+"-"+m+"-"+d+"!");
                
    //             @if($claim ?? '')
    //                 $("#inputdate").val("{{$claim->date}}");
    //             @elseif($draft ?? '')
    //                 $("#inputdate").val("{{$draft[4]}}");
    //             @else
    //                 $("#inputdate").val("");
    //             @endif
    //         }
    //     });

    @if(($c ?? '')||($d ?? '')||($q ?? ''))
        //check start time & end time
        function killview(i, m, s, e){
            // console.log("masuk");
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
                $("#inputduration-"+i).text('');
            }
            // return false;
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
                $("#inputduration-"+i).text(hm[0]+"h/"+hm[1]+"m");
            }
            nhm = showtime((parseInt(th*60)+parseInt(tm))+(parseInt(hm[0]*60)+parseInt(hm[1]))-(parseInt(odh*60)+parseInt(odm)));
            $("#olddh-"+i).text(hm[0]);
            $("#olddm-"+i).text(hm[1]);
            $("#oldth").text(nhm[0]);
            $("#oldtm").text(nhm[1]);
            $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
        }

        function checktime(i){
        // return function(){
            var clock_in = $("#inputstart-"+i).data('clock_in');
            var clock_out = $("#inputend-"+i).data('clock_out');
            var start_time = $("#inputstart-"+i).data('start_time');
            var end_time = $("#inputend-"+i).data('end_time');
            var cont = true;
            // alert($("#inputend-"+i).val());
            // console.log($("#inputstart-"+i).val());
            if($("#inputstart-"+i).val()=="24:00"){
                Swal.fire({
                    icon: 'error',
                    title: 'Input time error',
                    text: "Start time cannot be 24:00!"
                    // text: "End time must be more than "+sh+":"+sm+me+"!"
                })
                $("#inputstart-"+i).val("");
            }else{
                if(i!=0){
                    if($("#inputstart-"+i).val()==""){
                        $("#inputstart-"+i).val(start_time);
                    }else if($("#inputend-"+i).val()==""){
                        $("#inputend-"+i).val(end_time);
                    }
                }
                var clocker = $("#inputstart-"+i).data('clocker'); //start time
                // if(checker){
                //     if(clock_in!=""){
                //         if($("#inputstart-"+i).val()==""){
                //             $("#inputstart-"+i).val(start_time);
                //         }else if($("#inputend-"+i).val()==""){
                //             $("#inputend-"+i).val(end_time);
                //         }
                //     }
                //     checker = false;
                // }else{
                //     check=true;
                var time=[];
                // console.log(i);
                var st = ($("#inputstart-"+i).val()).split(":");
                var et = ($("#inputend-"+i).val()).split(":");
                @if($shift=="Yes")
                    @if($claim ?? '')
                        if($("#inputdate-"+i).val()=="{{date('d.m.Y', strtotime($claim->date))}}"){
                    @elseif($draft ?? '')
                        if($("#inputdate-"+i).val()=="{{date('d.m.Y', strtotime($draft[4]))}}"){
                    @endif
                            var min = "{{$day[0]}}";
                            var sc = "{{$day[0]}}"; 
                            var ec = "24:00"; 
                            @if($day[6])
                                var max = "{{$day[1]}}";
                            @else
                                @if($day[2]=="Public Holiday")
                                    var max = "{{$day[1]}}";
                                @else
                                    var max = "24:00";
                                @endif
                            @endif
                        }else{
                            var min = "00:00"; 
                            var sc = "00:00"; 
                            var ec = "{{$day[5]}}"; 
                            @if($day[6])
                                var max = "00:00"; 
                            @else
                                @if($day[2]=="Public Holiday")
                                    var max = "00:00";
                                @else
                                    var max = "{{$day[1]}}";
                                @endif
                            @endif
                        }
                @else
                    var min = "{{$day[0]}}";
                    var max = "{{$day[1]}}";
                    var sc = "00:00"; 
                    var ec = "24:00"; 
                @endif
                var mt = min.split(":");
                var mxt = max.split(":");
                var sdt = sc.split(":");
                var edt = ec.split(":");
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
                var sd = ((parseInt(sdt[0]))*60)+(parseInt(sdt[1]));
                var ed = ((parseInt(edt[0]))*60)+(parseInt(edt[1]));
                var start = ((parseInt(st[0]))*60)+(parseInt(st[1]));
                var end = ((parseInt(et[0]))*60)+(parseInt(et[1]));
                var nstart = ((parseInt(mt[0]))*60)+(parseInt(mt[1]));
                var nend = ((parseInt(mxt[0]))*60)+(parseInt(mxt[1]));
                // console.log(start + " s:"+nstart+" e:"+nend);
                
                if(start<sd){
                    killview(i, "Time input cannot be before "+sc+"!");
                    cont = false;
                }else if(start > ed){
                    killview(i, "Time input cannot be after "+ec+"!");
                    cont = false;
                }
                if($("#inputend-"+i).val()!=""){
                    if(end<sd){
                        killview(i, "Time input cannot be before "+sc+"!");
                        cont = false;
                    }else if(end > ed){
                        killview(i, "Time input cannot be after "+ec+"!");
                        cont = false;
                    }
                }
                if(cont){
                    if($("#inputend-"+i).val()!=""){
                        // if($("#inputend-"+i).val()=="00:00"){
                        //     var entime = "24:00";
                        // }else{
                        var entime = $("#inputend-"+i).val();
                        // }
                        var et = entime.split(":");
                        // var et = ($("#inputend-"+i).val()).split(":");
                        var end = ((parseInt(et[0]))*60)+(parseInt(et[1]));
                    }
                    if(clocker!=undefined){
                        time = timemaster(clock_in, clock_out);
                        // if(check){
                            if((time[0]<=start&&time[1]>=start)&&(time[0]<=end&&time[1]>=end)){
                                calshowtime(i, end-start, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                            }else{
                                calshowtime(i, (parseInt($("#fixdh-"+i).text()*60)+parseInt($("#fixdm-"+i).text())), $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                check = killview(i, "Time input must be within time range from "+clock_in+" to "+clock_out+"!", (clock_in), clock_out);
                            }
                        // }   
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
                                    // if(check){
                                        if(start > time[0] && start < time[1]){
                                            if(i!=0){
                                                if(!($('#oldds-'+n).text()==start_time)){
                                                    calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());   
                                                    killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                    // check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                }
                                            }
                                            else if(n!=0){
                                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());  
                                                killview(i, "Time input cannot be within inserted time range!", start_time, end_time);  
                                                // check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);  
                                            }
                                        }
                                    // }
                                }
                                @endif
                        @endif
                        //check if within working time or not
                        // if(check){
                            console.log(start+" "+nstart+" "+nend+" "+min+" "+max);
                            if(start > nstart && start < nend){
                                // check = killview(i, "Time input cannot be between {{$day[0]}} and {{$day[1]}}!", start_time, end_time);
                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                
                                @if($day[6])
                                    killview(i, "Time input cannot be between {{$day[0]}} and @if($day[1]=='00:00') 24:00 @else {{$day[1]}} @endif!", start_time, end_time);
                                @else
                                    killview(i, "Time input cannot be between {{$day[0]}} {{date("d.m.Y", strtotime($day[7]))}} and {{$day[1]}} {{date("d.m.Y", strtotime($day[7]."+1 day"))}}!", start_time, end_time);
                                @endif
                            }
                        // }
                        if($("#inputstart-"+i).val()!="" && $("#inputend-"+i).val()!=""){
                            // alert(start+" "+end);
                            if(start<end){
                                @if($claim ?? '')
                                    @if(count($claim->detail)!=0)
                                        for(n=0; n<{{$no}}+1; n++){
                                            time = timemaster($('#oldds-'+n).text(), $('#oldde-'+n).text());
                                            // if(check){
                                                if((time[0]<end&&time[1]>start)){
                                                    if(i!=0){
                                                        if(!($('#oldds-'+n).text()==start_time)){
                                                            if(n!=i){
                                                                calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                                                killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                                // check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                            }
                                                        }
                                                    }
                                                    else if(n!=0){
                                                        calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                                        killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                        // check = killview(i, "Time input cannot be within inserted time range!", start_time, end_time);
                                                    }
                                                }
                                            // }
                                        }
                                    @endif
                                @endif
                                // if(check){
                                    if((end>nstart && end<nend)||(nstart<end && nend>start)){
                                        calshowtime(i, 0, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                        killview(i, "Time input cannot be between {{$day[0]}} {{date("d.m.Y", strtotime($day[7]))}} and {{$day[1]}} {{date("d.m.Y", strtotime($day[7]."+1 day"))}}!");
                                        // check = killview(i, "Time input cannot be between {{$day[0]}} and {{$day[1]}}!");
                                    }
                                    else{
                                        calshowtime(i, end-start, $("#olddh-"+i).text(), $("#olddm-"+i).text(), $("#oldth").text(), $("#oldtm").text());
                                        // if(i!=0){
                                        $('#oldds-'+i).text($("#inputstart-"+i).val());
                                        $('#oldde-'+i).text($("#inputend-"+i).val());
                                    }
                                // }
                            }else{
                                // if(check){
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
                                // }
                            }
                        }
                    } 
                } 
                // checker = true;        
            }    
        }              
            // };
        // };

        function checkbox(i){
            return function(){
                @if($shift=="Yes")
                    $("#inputdate-0").prop('required',false);
                @endif
                $("#inputstart-0").prop('required',false);
                $("#inputend-0").prop('required',false);
                $("#inputremark-0").prop('required',false);
                // alert( $("#inputcheck-1").val());
                if ($('#inputcheck-'+i).is(':checked')){
                    $('#inputcheckdata-'+i).val("Y");
                }else{
                    $('#inputcheckdata-'+i).val("N");
                }
                $("#formtype").val("save");
                $("#form").submit();
                return saves();
                // if ($('#inputcheck-'+i).is(':checked')){
                //     $('#inputcheckdata-'+i).val("Y");
                //     calshowtime(i, (parseInt($("#olddh-"+i).text()*60)+parseInt($("#olddm-"+i).text())), 0, 0, $("#oldth").text(), $("#oldtm").text());
                // }else{
                //     // $('#inputjustification').val("N");
                //     $('#inputcheckdata-'+i).val("N");
                //     nhm = showtime((parseInt($("#oldth").text()*60)+parseInt($("#oldtm").text()))-(parseInt($("#olddh-"+i).text()*60)+parseInt($("#olddm-"+i).text())));
                //     $("#oldth").text(nhm[0]);
                //     $("#oldtm").text(nhm[1]);
                //     $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
                // }
            }
        }

        function deleteid(i){
            return function(){
                var id = $("#delete-"+i).data('id');
                var ss = $("#delete-"+i).data('start');
                var ee = $("#delete-"+i).data('end');
                if(ee=="00:00"){
                    ee = "24:00";
                }
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
            $("#inputstart-"+i).change(timepicker(i, "#"+$("#inputstart-"+i).attr("id")));
            $("#inputend-"+i).change(timepicker(i, "#"+$("#inputend-"+i).attr("id")));
            $("#inputcheck-"+i).change(checkbox(i));
            $("#delete-"+i).on('click', deleteid(i));
        };
        
        
        $("#inputdate-0").change(function(){
            $("#inputstart-0").prop('disabled', false);
        });

        function timepicker(i, id){
            return function(){
                var splits = $(id).val().split("");
                var h1 = "0";
                var h2 = "0";
                var m1 = "0";
                var m2 = "0";
                // console.log(splits);
                // alert(split.length);
                // var splice = null;
                var length = splits.length;
                for(var x=0; x<length; x++){
                    if(splits[x]==":"){
                        splits.splice(x, 1);
                        length = length - 1;
                    }
                }
                
                // console.log(splits);
                if(splits.length==1){
                    if(!(isNaN(parseInt(splits[0])))){
                        h2 = splits[0];
                    }
                    $(id).val(h1+h2+":"+m1+m2);
                }else{
                    if(splits.length>0){
                        if(!(isNaN(parseInt(splits[0])))){
                            if(parseInt(splits[0])>2){
                                h1 = "2";
                            }else{
                                h1 = splits[0];
                            }
                        }
                        if(splits.length>1){
                            if(!(isNaN(parseInt(splits[1])))){
                                if(parseInt(h1)==2){
                                    if(parseInt(splits[1])>4){
                                        h2 = "4";
                                    }else{
                                        h2 = splits[1];
                                    }
                                }else{
                                    h2 = splits[1];
                                }
                            }
                            if(splits.length>2){
                                if(splits.length==3){
                                    h1 = "0";
                                    h2 = splits[0];
                                    m1 = splits[1];
                                    m2 = splits[2];
                                }else{
                                    if(!(parseInt(h1 + h2)>23)){
                                        if(!(isNaN(parseInt(splits[2])))){
                                            // if(parseInt(h1)==2){
                                                if(parseInt(splits[2])>5){
                                                    m1 = "5";
                                                }else{
                                                    m1 = splits[2];
                                                }
                                            // }else{
                                            //     h2 = split[1];
                                            // }
                                        }
                                        if(!(isNaN(parseInt(splits[3])))){
                                            m2 = splits[3];
                                        }
                                    }
                                }
                            }
                        }
                    }                    
                }
                // console.log("h1:"+h1+", h2:"+h2+", m1:"+m1+", m2:"+m2);
                // console.log(id);
                $(id).val(h1+h2+":"+m1+m2);
                return checktime(i);
            }
        }
        //when click delete file
        function deletefile(i){
            return function(){
                var id = $("#btn-file-del-"+i).data('id');
                var img = $("#btn-file-del-"+i).data('img');
                var name = $("#btn-file-del-"+i).data('name');
                Swal.fire({
                    html: 
                    '<h5>Are you sure to delete file '+name+'?</h5>'+
                    '<img src="'+img+'" class="img-fluid img-thumbnails" style="height: 300px; width: 300px; border: 1px solid #A9A9A9">',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete'
                    }).then((result) => {
                    if (result.value) {
                        $("#filedel").val(id);
                        $("#inputstart-0").val("");
                        $("#inputend-0").val("");
                        $("#inputremark-0").val("");
                        @if($shift=="Yes")
                            $("#inputdate-0").prop('required',false);
                        @endif
                        $("#inputstart-0").prop('required',false);
                        $("#inputend-0").prop('required',false);
                        $("#inputremark-0").prop('required',false);
                        $("#formtype").val("delete");
                        $("#form").submit();
                    }
                })
                return false;  
            }
        }

        for(i=0; i<
            @if($claim ?? '') 
                {{count($claim->file)+1}} 
            @else 
                1 
            @endif; i++) {
            $("#btn-file-del-"+i).on('click', deletefile(i));
        };
        
        //when click add time
        var canadd = true
        var addsubmit = true;
        $("#add").on('click', function(){
            @if($claim ?? '')
            for(j=1; j<{{count($claim->detail)}}+1;j++){
                if($(".check-"+j+"-0").prop("checked") == true){
                    // alert(yes);
                    for(m=1; m<4; m++){
                        if($('.check-'+j+"-"+m).get(0).checkValidity()==false){
                            $('.check-'+j+"-"+m).get(0).reportValidity();
                            canadd = false;
                        }
                    }
                }
            }
            @endif
            // alert(canadd);
            
            if(($('#inputstart-0').val()=="")||($('#inputend-0').val()=="")||($('#inputremark-0').val()=="")){
            // if(($('#inputstart-0').val()==="")){
                // alert("sad");
                addsubmit = false;
            }
            if(canadd){
                if(add){
                    // $('#oldds-0').text($("#inputstart-0").val());
                    // $('#oldde-0').text($("#inputend-0").val());
                    $('#addform').css("display", "table-row");
                    @if($shift=="Yes")
                        $("#inputdate-0").prop('required',true);
                    @endif
                    $("#inputstart-0").prop('required',true);
                    $("#inputend-0").prop('required',true);
                    $("#inputremark-0").prop('required',true);
                    // calshowtime(0, (parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())), 0, 0, $("#    oldth").text(), $("#oldtm").text());
                    $('#nodata').css("display","none");
                    // $('#add').prop("disabled",true);
                    add=false;  
                    addsubmit = true;
                }else{
                //     for(j=0; j<3;j++){
                //         if($('.check-0-'+j).get(0).checkValidity()==false){
                //             $('.check-0-'+j).get(0).reportValidity();
                //         }
                //     }
                // alert($('#inputstart-0').val());
                // alert($('#inputend-0').val());
                // alert($('#inputremark-0').val());
                // alert(addsubmit);
                // alert(add);
                    if(addsubmit){
                        // $("#formtype").val("add");
                        // $("#form").submit();
                    }else{
                        
                        addsubmit = true;
                        Swal.fire({
                            icon: 'error',
                            title: 'Incomplete',
                            text: 'Please complete current input fields before adding a new one!'
                        })
                    }
                }
            }else{
                if(addsubmit){
                    // $("#formtype").val("add");
                    // $("#form").submit();
                }else{
                        addsubmit = true;
                    Swal.fire({
                        icon: 'error',
                        title: 'Incomplete',
                        text: 'Please complete current input fields before adding a new one!'
                    })
                }
            }
        });
        
        //when cancel add time
        $("#cancel").on('click', function(){
            if(!(add)){
                $('#oldds-0').text("0");
                $('#oldde-0').text("0");
                $('#addform').css("display", "none");
                $("#inputduration-0").text('');
                $("#inputstart-0").val('');
                $("#inputend-0").val('');
                @if($shift=="Yes")
                    $("#inputdate-0").prop('required',false);
                @endif
                $("#inputstart-0").prop('required',false);
                $("#inputend-0").prop('required',false);
                @if($shift=="Yes")
                    $("#inputstart-0").prop('disabled',true);
                @endif
                $("#inputend-0").prop('disabled',true);
                $("#inputremark-0").prop('required',false);
                nhm = showtime((parseInt($("#oldth").text()*60)+parseInt($("#oldtm").text()))-(parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())));
                $('#olddh-0').text("0");
                $('#olddm-0').text("0");
                $("#oldth").text(nhm[0]);
                $("#oldtm").text(nhm[1]);
                $("#showtime").text(nhm[0]+"h "+nhm[1]+"m");
                $('#nodata').css("display","table-row");
                // $('#add').prop("disabled",false);
                add=true;  
            }
        });

        $("#btn-file-1").on('click', function(){
            $('#inputfile').trigger('click');   
        });

        $("#btn-file-2").on('click', function(){
            $("#btn-file-1").css("display", "initial");
            $("#btn-file-2").css("display", "none");
            $("#btn-file-3").css("display", "none");
            $("#inputfile").val("");
            $("#inputfiletext").text("File: .bmp, .pdf, .png, .tiff");
            return false;  
        });

        //when uploading file
        $("#btn-file-3").on('click', function(){
            $("#inputstart-0").val("");
            $("#inputend-0").val("");
            $("#inputremark-0").val("");
            @if($shift=="Yes")
                $("#inputdate-0").prop('required',false);
            @endif
            $("#inputstart-0").prop('required',false);
            $("#inputend-0").prop('required',false);
            $("#inputremark-0").prop('required',false);
            // $("#formsave").val("save");
            // $("#formsubmit").val("no");
            $("#formtype").val("save");
            $("#form").submit();
            return saves();
        });  

        $("#inputfile").on("change", function(){
            var filesize = this.files[0].size;
            if (filesize > 1000000) { 
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'File size has exceeded 1MB!'
                })
                $("#inputfile").val("");
                $("#inputfiletext").text("File: .bmp, .pdf, .png, .tiff");
            }
            else{
                var ret = $("#inputfile").val().replace("C:\\fakepath\\",'');
                $("#inputfiletext").text(ret);
                $("#btn-file-1").css("display", "none");
                $("#btn-file-2").css("display", "initial");
                $("#btn-file-3").css("display", "initial");
            }
        });
    @endif

    //when adding new time
    //oldcode---------------------------------------
    // $("#btn-add").on('click', function(){
    //     for(j=0; j<3;j++){
    //         if($('.check-'+j).get(0).checkValidity()==false){
    //             // $('.check-2').get(0).reportValidity();
    //             $('.check-'+j).get(0).reportValidity();
    //             submit = false;
    //         }else{
    //             submit = true;
    //         }
    //     }
    //     if(submit){
    //         // $("#formadd").val("add");
    //         // $("#formsubmit").val("no");
    //         $("#formtype").val("add");
    //         $("#form").submit();
    //     }
    // });  
    //oldcode---------------------------------------
    
    function addot(i){
        return function(){
            submit = true;
            for(j=0; j<3;j++){
                if($('.check-'+i+'-'+j).get(0).checkValidity()==false){
                    submit = false;
                }
            }
            // alert(submit+" "+check+" "+$('.check-1').val());   
            if(submit){
                if($('.check-'+i+'-1').val()!=""){
                    // if(checker){
                        if(check){
                            if(i==0){
                                $("#formtype").val("add");
                                $("#form").submit();
                                return saves();
                            }else{
                                $("#formtype").val("save");
                                $("#form").submit();
                                return saves();
                            }
                        }
                    // }
                }
            }
        }
    }
    
    for(i=0; i<{{$no ?? ''}}+1;i++){
        $('.check-'+i).on('change', addot(i))
    }

    //when saving form
    // $("#btn-save").on('click', function(){
    //     if(add){
    //         // $("#formsave").val("save");
    //         // $("#formsubmit").val("no");
    //         $("#formtype").val("save");
    //         $("#form").submit();
    //     }else{
    //         // alert("Please save new time input before saving the form!");
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Unable to save form',
    //             text: 'Please save new time input before saving the form!'
    //         })
    //     }
    // });  
    $("#sub").on("click", function(){
        if(($("#formtype").val()=="add")){
            return false;
        }else{
            @if(($c ?? '')||($d ?? '')||($q ?? ''))
                @if($shift=="Yes")
                    $("#inputdate-0").prop('required',false);
                @endif
            @endif
            $("#inputstart-0").prop('required',false);
            $("#inputend-0").prop('required',false);
            $("#inputremark-0").prop('required',false);
        }
    })

//   $(window).keydown(function(event){
//     if(event.keyCode == 13) {
//       event.preventDefault();
//       return false;
//     }
//   });

    function submission(){
        if(($("#formtype").val()=="submit")){          
            if(@if($claim ?? ''){{count($claim->detail)}}@else 0 @endif!=0){
                if(whensubmit){
                    // if(add){
                        Swal.fire({
                            title: 'Terms and Conditions',
                            input: 'checkbox',
                            inputValue: 0,
                            inputPlaceholder:
                                "<p>By clicking on <span style='color: #143A8C'>\"Yes\"</span> button below, you are agreeing to the above related terms and conditions</p>",
                                html: "<p>I hereby certify that my claim is compliance with company's term and condition on <span style='font-weight: bold'>PERJANJIAN BERSAMA, HUMAN RESOURCE MANUAL, and BUSINESS PROCESS MANUAL</span> If deemed falsed, disciplinary can be imposed on me.</p>",
                               confirmButtonText:
                                'YES',
                                cancelButtonText: 'NO',
                            showCancelButton: true,
                            confirmButtonColor: '#EF7202',
                            cancelButtonColor: 'transparent',
                            inputValidator: (result) => {
                                return !result && 'You need to agree with T&C'
                            }
                        }).then((result) => {
                            if (result.value) {
                                whensubmit = false;
                                
                             @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                @if($shift=="Yes")
                                    $("#inputdate-0").val(null);
                                @endif
                            @endif
                                $("#inputstart-0").val(null);
                                $("#inputend-0").val(null);
                                $("#inputremark-0").val(null);
                                $("#form").submit();
                                return submits();
                            }else{
                                
                            @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                @if($shift=="Yes")
                                    $("#inputdate-0").prop('required',true);
                                @endif
                            @endif
                                $("#inputstart-0").prop('required',true);
                                $("#inputend-0").prop('required',true);
                                $("#inputremark-0").prop('required',true);
                            }
                        })
                        return false;
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to submit form',
                    text: 'Please add time to your claim'
                })
                
            @if(($c ?? '')||($d ?? '')||($q ?? ''))
                @if($shift=="Yes")
                    $("#inputdate-0").prop('required',true);
                @endif
            @endif
                $("#inputstart-0").prop('required',true);
                $("#inputend-0").prop('required',true);
                $("#inputremark-0").prop('required',true);
                return false;
            }
        }
    }

    $("#chargetype").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
        return saves();
    });   

    $("#costc").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
        return saves();
    });    
    
    $("#compn").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
        return saves();
    });    

    $("#type").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
        return saves();
    });    
    // $("#orderno").change(function(){
    //     $("#formtype").val("save");
    //     $("#form").submit();
    // });    
    $("#ordernosearch").on('click', function(){
        // alert("x"+$("#orderno").val());
        search($("#orderno").val());
        // $("#formtype").val("save");
        // $("#form").submit();
    });    
    var htmlstring;
    var searchtml;
    var checkorder = null;
    var potype;
    var number;
    var checkselect = false;

    function search(orderno){
        checkorder = orderno;
        checkselect = false;
        htmlstring = '<div style="border: 1px solid #DDDDDD; max-height: 60vh; overflow-y: scroll;  overflow-x: hidden;">';
        searchtml = "<div class='text-left swollo'>"+
                        "<input id='namet' placeholder=\"Enter"+ 
                        @if($claim ?? '')
                            @if($claim->charge_type=="Project")
                                " project "+
                            @else
                                " order "+
                            @endif
                        @endif
                        "no\" style='width: 100%; box-sizing: border-box;' onkeyup='this.onchange();' onchange='return checkstring();'>"+
                        "<button type='button' id='namex' onclick='return cleart()' class='approval-search-x btn-no'>"+
                            "<i class='far fa-times-circle'></i>"+
                        "</button>"+
                        "<button type='button' id='namex' onclick=\"if(($('#namet').val().length)>3){ if($('#namet').val()==''){ $('#checker').css('display','block'); }else{ return search($('#namet').val())}}else{ $('#namex').css('visibility','hidden'); $('#3more').css('display','block'); $('#margin').css('margin-left','0');}\" class='approval-search-icon btn-no'>"+
                            "<i class='fas fa-search'></i>"+
                        "</button>"+
                        "<p id='3more' style=' margin-top: -15px; color: #F00000; display: none'>Search input must be more than 3 alphabets!</p>"+
                        "<p id='checker' style=' margin-top: -15px; color: #F00000; display: none'>Please fill in"+
                        @if($claim ?? '')
                            @if($claim->charge_type=="Project")
                                " project "+
                            @else
                                " order "+
                            @endif
                        @endif
                        "no before searching!</p>"+
                        "<p id='sel' style=' margin-top: -15px; color: #F00000; display: none'>Please select"+
                        @if($claim ?? '')
                            @if($claim->charge_type=="Project")
                                " project "+
                            @else
                                " order "+
                            @endif
                        @endif
                        "no!</p>"+
                    "</div>";
        if(orderno!=""){
            @if($claim ?? '')
                @if($claim->charge_type=="Project")
                    potype = "project";
                @elseif($claim->charge_type=="Internal Order")
                    potype = "internal";
                @else
                    potype = "maintenance";
                @endif
            @endif
            const url='{{ route("ot.searchod", [], false)}}';
            $.ajax({
                type: "GET",
                url: url+"?order="+orderno+"&type="+potype,
                success: function(resp) {
                    var classc="test3"   
                    var confirm = "SELECT"; 
                    if(resp.length>0){
                        number = resp.length;
                        resp.forEach(updateResp);
                        classc="test2"    
                    }else{
                        confirm = "SEARCH"; 
                        htmlstring = "<div style=' width: 100%; padding: 5px; text-align: center; vertical-align: middle'>"+
                                    "<p>No matching records found. Try to search again.</p>"+
                                    "</div>";
                    }
                    Swal.fire({
                        @if($claim ?? '')
                            @if($claim->charge_type=="Project")
                                title: "Project No",
                            @else
                                title: "Order No",
                            @endif
                        @endif
                        customClass: classc,
                        html: searchtml +
                                "<div class='text-left'>"+htmlstring+"</div>",
                        confirmButtonText: confirm,
                        showCancelButton: true,
                        cancelButtonText: 'CANCEL',
                        preConfirm: function() {
                            if(confirm=="SELECT"){
                                if(checkselect){

                                    $("#formtype").val("save");
                                    $("#form").submit();
                                    return saves();
                                }else{
                                    
                                    $('#sel').css('display','block');
                                }
                            }else{
                                if(($('#namet').val().length)>3){
                                    if($('#namet').val()==''){ 
                                        $('#checker').css('display','block');
                                        return false;
                                    }else{
                                        search($('#namet').val());
                                    }
                                }else{
                                    $('#namex').css('visibility','hidden');
                                    $('#3more').css('display','block');
                                    $('#margin').css('margin-left','0');
                                    return false;
                                }
                            }
                        }
                    }).then((result) => {
                        if (result.value) {

                        }else{
                            @if($claim ?? '')
                                @if($claim->project_no!=null)
                                    $('#orderno').val("{{$claim->project_no}}");
                                @elseif($claim->order_no!=null)
                                    $('#orderno').val("{{$claim->order_no}}");
                                @else
                                    $('#orderno').val("");
                                @endif
                            @endif
                        }
                    });
                }
            });
        }else{
            Swal.fire({
                @if($claim ?? '')
                    @if($claim->charge_type=="Project")
                        title: "Project No",
                    @else
                        title: "Order No",
                    @endif
                @endif
                customClass: 'test3',
                html: searchtml +
                        "<div class='text-left'>"+htmlstring+"</div>",
                confirmButtonText: 'SEARCH',
                showCancelButton: true,
                cancelButtonText: 'CANCEL',
                preConfirm: function() {
                    if(($('#namet').val().length)>3){
                        if($('#namet').val()==''){ 
                            $('#checker').css('display','block');
                            return false;
                        }else{
                            search($('#namet').val());
                        }
                    }else{
                        $('#namex').css('visibility','hidden');
                        $('#3more').css('display','block');
                        $('#margin').css('margin-left','0');
                        return false;
                    }
                }
            });
        }
    }

    function updateResp(item, index){
        var type;
        if(potype == 'project'){
            type = 'Project';
        }else{
            type = 'Order';
        }
        var border="";
        // console.log(checkorder);
        // console.log(item.id);
        if(checkorder==item.id){
            border = "outline: 1px solid #143A8C; border: 2px solid #143A8C";
        }
        htmlstring = htmlstring + 
            "<button style='border: 1px solid #DDDDDD; min-height: 10vh; width: 100%; padding: 5px; text-align: left; background: transparent "+border+"' onclick='selectno(\""+item.id+"\","+index+");' id='addv-"+index+"'>"+
                "<div style='display: flex; align-items: center; flex-wrap: wrap; width: 95%; margin-left: 3% padding: 15px '>"+
                    "<div class='w-50 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'> "+type+" No<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.id+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Description<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'><span class='dm'>: </span></span><b>"+item.descr+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Type<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.type+"</b></div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='w-50 m-15'>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Company Code<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.costc+"</b></div>"+
                        "</div>"+
                        "<div class='approval-search-item'>"+
                            "<div class='w-30'>Company Code<span class='dmx'>:</span></div>"+
                            "<div class='w-70'><span class='dm'>: </span><b>"+item.comp+"</b></div>"+
                        "</div>"+
                    "</div>"+
                "</div>"+
            "</button>";
            
    }

    function selectno(id, num){
        $('#orderno').val(id);
        checkselect = true;
        for(i = 0; i<number; i++){
            if(i!=num){
                $('#addv-'+i).css('outline','none');
                $('#addv-'+i).css('border','1px solid #DDDDDD');
            }else{
                $('#addv-'+i).css('outline','1px solid #143A8C');
                $('#addv-'+i).css('border','2px solid #143A8C');
            }
        }
    }

    function cleart(){
        $('#namet').val('');
        $('#namex').css('visibility','hidden');
    }

    function checkstring(){
        $('#checker').css('display', 'none');
        if(($('#namet').val().length)>3){
            $('#namex').css('visibility', 'visible');
            $('#3more').css('display', 'none');
            $('#margin').css('margin-left', '-20px');
        }else{
            $('#namex').css('visibility','hidden');
            $('#3more').css('display','block');
            $('#margin').css('margin-left','0');
        }
    }

    $("#networkh").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
        return saves();
    });    
    $("#networkn").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
        return saves();
    });    
    $("#approvern").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
        return saves();
    });    

    function saves(){
        // $('input[name="inputact[]"').eq(2).val("A");
            // alert($('#action-3').val());
            // return false;
        Swal.fire({
            title: 'Auto-save form',
            html: 'Please wait while we save your claim. <b>DO NOT RELOAD/CLOSE THIS TAB!</b>',
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            showCancelButton: false,
            customClass: "load",
            onBeforeOpen: () => {
            Swal.showLoading()}
        })
        // return false;
    }

    function submits(){
        // $('input[name="inputact[]"').eq(2).val("A");
            // alert($('#action-3').val());
            // return false;
        Swal.fire({
            title: 'Submitting form',
            html: 'Please wait while we process your submission. <b>DO NOT RELOAD/CLOSE THIS TAB!</b>',
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            showCancelButton: false,
            customClass: "load",
            onBeforeOpen: () => {
            Swal.showLoading()}
        })
        // return false;
    }
function deletes(){
        // $('input[name="inputact[]"').eq(2).val("A");
            // alert($('#action-3').val());
            // return false;
        Swal.fire({
            title: 'Deleting time',
            html: 'Please wait while we delete your time. <b>DO NOT RELOAD/CLOSE THIS TAB!</b>',
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            showCancelButton: false,
            customClass: "load",
            onBeforeOpen: () => {
            Swal.showLoading()}
        })
        // return false;
    }


    @if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif
</script>
@stop
           