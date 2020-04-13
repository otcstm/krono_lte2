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
        <div class="panel-heading panel-primary">Overtime Application</div>
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
                        <p>OT Date: <input type="text" class='datepicker-here' data-language='en' data-date-format="yyyy-mm-dd" id="inputdate" name="inputdate" 
                            
                            {{--@if($claim ?? '')
                                value="{{$claim->date}}"
                            @elseif($draft ?? '')
                                value="{{date('Y-m-d', strtotime($draft[4]))}}"
                            @else
                                value=""
                            @endif --}}
                            required  onkeydown="return false">
                            <!-- <button type="button" id="btn-date" class="btn btn-primary" style="padding: 2px 3px; margin: 0; margin-top: -3px;"><i class="fas fa-share-square"></i></button> -->
                        </p>
                    </form>    
                        <p>Day Type:
                            @if($claim ?? '')  
                                {{$claim->daytype->description}}
                            @elseif(($draft ?? ''))
                                {{$draft[8]}}
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
                        @endif
                    </p>
                    <p>Verifier: 
                        @if($claim ?? '') 
                            {{$claim->verifier->name}}
                        @elseif($draft ?? '')
                            {{$draft[9]}} 
                        @else 
                            N/A 
                        @endif
                    </p>
                    <p>Approver: 
                        @if($claim ?? '')
                            {{$claim->approver->name}}
                        @elseif($draft ?? '')
                            {{$draft[10]}}
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
                                                <td>
                                                    @if(($c ?? '')||($d ?? '')||($q ?? ''))
                                                        <span id="oldds-{{$no}}" class="hidden">{{date('H:i', strtotime($singleuser->start_time))}}</span>
                                                        <input style="width: 40px" id="inputstart-{{$no}}" name="inputstart[]" type="text" class="timepicker check-{{$no}} check-{{$no}}-1 @if($singleuser->checked=="N") hidden @endif" 
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
                                                        <span id="oldde-{{$no}}" class="hidden">{{date('H:i', strtotime($singleuser->end_time))}}</span>
                                                        <input style="width: 40px" id="inputend-{{$no}}" name="inputend[]" type="text" class="timepicker check-{{$no}} check-{{$no}}-2 @if($singleuser->checked=="N") hidden @endif" 
                                                            data-clock_out="{{ date('H:i', strtotime($singleuser->clock_out))}}"
                                                            data-end_time="{{ date('H:i', strtotime($singleuser->end_time))}}"
                                                            value="{{ date('H:i', strtotime($singleuser->end_time))}}" required>
                                                        @if($singleuser->checked=="N")
                                                            {{ date('Hi', strtotime($singleuser->end_time)) }}
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
                                    <tr id="nodata" class="text-center"><td colspan="9"><i>Not Available</i></td></tr>
                                @endif
                            @else
                                <tr id="nodata" class="text-center"><td colspan="9"><i>Not Available</i></td></tr>
                            @endif
                            <tr id="addform" style="display: none">
                                <td></td>
                                <td>@if($claim ?? '') {{count($claim->detail)+1}} @else 1 @endif</td>
                                <!-- <td>Manual Input</td> -->
                                <td>
                                    <span id="oldds-0" class="hidden">0</span>
                                    <input style="width: 40px" id="inputstart-0" type="text" name="inputstartnew" class="timepicker check-0 check-0-0">
                                </td>
                                <td>
                                    <span id="oldde-0" class="hidden">0</span>
                                    <input style="width: 40px" id="inputend-0" type="text" name="inputendnew" class="timepicker check-0 check-0-1" disabled>
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
                                                <select class="form-select" name="orderno" id="orderno" required @if($orderno==null) disabled @endif>
                                                    <option value="" @if($claim->project_type==NULL) selected @endif hidden>Select @if($claim->charge_type=="Project") project @else order @endif no</option>
                                                    @if($orderno!=null)
                                                        @foreach($orderno as $singleorder)
                                                            @if($claim->charge_type=="Project") 
                                                                <option value="{{$singleorder->project_no}}" @if($claim->project_no==$singleorder->project_no) selected @endif>{{$singleorder->project_no}}</option>
                                                            @else
                                                                <option value="{{$singleorder->id}}" @if($claim->order_no==$singleorder->id) selected @endif>{{$singleorder->id}}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select> 
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
                                                        <option value="" @if($claim->company_code==NULL) selected @endif hidden>Select company code</option>
                                                        @if($compn!=null)
                                                            @foreach($compn as $singlecompn)
                                                                <option value="{{$singlecompn->company_id}}" @if($claim->company_id==$singlecompn->company_id) selected @endif>{{$singlecompn->company_id}}</option>
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
                                                    <input type="text" class="form-select"  value="{{$claim->costcenter}}">
                                                @elseif($claim->charge_type=="Other Cost Center")
                                                    <select class="form-select" name="costc" id="costc" required @if($costc==null) disabled @endif>
                                                        @if($costc!=null)
                                                            <option value="" @if($claim->other_costcenter==NULL) selected @endif hidden>Select cost center</option>
                                                            @foreach($costc as $singlecostc)
                                                                <option value="{{$singlecostc->id}}" @if($claim->other_costcenter==$singlecostc->id) selected @endif>{{$singlecostc->id}}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="" @if($costc==null) selected @endif hidden>Select cost center</option>
                                                        @endif
                                                    </select> 
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
                                                    <input type="text" class="form-select" @if($data!=NULL) value="{{--$data->name->name--}}" @endif disabled>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{--@if($claim ?? '')
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
                                                <select class="form-select" name="networkh" id="networkh" required @if($networkh==null) disabled @endif>
                                                    <option value="" @if($claim->project_type==NULL) selected @endif hidden>Select network header</option>
                                                @if($networkh!=null)
                                                    @foreach($networkh as $singlenet)
                                                        <option value="{{$singlenet->network_header}}" @if($claim->network_header==$singlenet->network_header) selected @endif>{{$singlenet->network_header}}</option>
                                                    @endforeach
                                                @endif
                                            </select> 
                                        </div>
                                    </div>

                                <!-- network activity no-->
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="col-md-3">
                                            <label>Network Activity No:</label>
                                        </div>
                                        <div class="col-md-9">
                                                <select class="form-select" name="networkn" id="networkn" required @if($networkn==null) disabled @endif>
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
                                @endif--}}

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
                <form id="delete" class="hidden" action="{{route('ot.formdelete')}}" method="POST">
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
                $("#formdate").submit();
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
                    check=true;
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

        $("#add").on('click', function(){
            @if($claim ?? '')
            for(j=1; j<{{count($claim->detail)}}+1;j++){
                if($(".check-"+j+"-0").prop("checked") == true){
                    // alert(yes);
                    for(m=1; m<4;m++){
                        if($('.check-'+j+"-"+m).get(0).checkValidity()==false){
                            $('.check-'+j+"-"+m).get(0).reportValidity();
                            canadd = false;
                        }
                    }
                }
            }
            @endif
            // alert(canadd);
            if(canadd){
                if(add){
                    // $('#oldds-0').text($("#inputstart-0").val());
                    // $('#oldde-0').text($("#inputend-0").val());
                    $('#addform').css("display", "table-row");
                    $("#inputstart-0").prop('required',true);
                    $("#inputend-0").prop('required',true);
                    $("#inputremark-0").prop('required',true);
                    // calshowtime(0, (parseInt($("#olddh-0").text()*60)+parseInt($("#olddm-0").text())), 0, 0, $("#    oldth").text(), $("#oldtm").text());
                    $('#nodata').css("display","none");
                    // $('#add').prop("disabled",true);
                    add=false;  
                }else{
                //     for(j=0; j<3;j++){
                //         if($('.check-0-'+j).get(0).checkValidity()==false){
                //             $('.check-0-'+j).get(0).reportValidity();
                //         }
                //     }
                    Swal.fire({
                        icon: 'error',
                        title: 'Incomplete',
                        text: 'Please complete current input fields before adding a new one!'
                    })
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Incomplete',
                    text: 'Please complete current input fields before adding a new one!'
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
            $("#inputstart-0").prop('required',false);
            $("#inputend-0").prop('required',false);
            $("#inputremark-0").prop('required',false);
            // $("#formsave").val("save");
            // $("#formsubmit").val("no");
            $("#formtype").val("save");
            $("#form").submit();
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
                    if(checker){
                        if(check){
                            if(i==0){
                                $("#formtype").val("add");
                                $("#form").submit();
                            }else{
                                $("#formtype").val("save");
                                $("#form").submit();
                            }
                        }
                    }
                }
            }
        }
    }
    
    for(i=0; i<{{$no ?? ''}}+1;i++){
        $('.check-'+i).on('change', addot(i))
    }

    // function addot(){
    //     return function(){
    //         submit = true;
    //         for(j=0; j<3;j++){
    //             if($('.check-'+j).get(0).checkValidity()==false){
    //                 submit = false;
    //             }
    //         }
    //         // alert(submit+" "+check+" "+$('.check-1').val());   
    //         if(submit){
    //             if($('.check-1').val()!=""){
    //                 if(checker){
    //                     if(check){
    //                         $("#formtype").val("add");
    //                         $("#form").submit();
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // for(i=0; i<3;i++){
    //     $('.check-'+i).on('change', addot())
    // }


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
            $("#inputstart-0").prop('required',false);
            $("#inputend-0").prop('required',false);
            $("#inputremark-0").prop('required',false);
        }
    })

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
                                $("#inputstart-0").val(null);
                                $("#inputend-0").val(null);
                                $("#inputremark-0").val(null);
                                $("#form").submit();
                            }else{
                                $("#inputstart-0").prop('required',true);
                                $("#inputend-0").prop('required',true);
                                $("#inputremark-0").prop('required',true);
                            }
                        })
                        // Swal.fire({
                        //     title: 'Are you sure to submit form?',
                        //     // text: "I hereby certify that my claim is compliance with company's term and condition on PERJANJIAN BERSAMA, HUMAN RESOURCE MANUAL, and BUSINESS PROCESS MANUAL If deemed falsed, disciplinary can be imposed on me.",
                        //     html: "<p style='color: red; font-weight: bold'>I hereby certify that my claim is compliance with company's term and condition on PERJANJIAN BERSAMA, HUMAN RESOURCE MANUAL, and BUSINESS PROCESS MANUAL If deemed falsed, disciplinary can be imposed on me.<br><br>By clicking on \"Yes\" button below, you are agreeing to the above related terms and conditions</p>",
                        //     icon: 'warning',
                        //     showCancelButton: true,
                        //     confirmButtonColor: '#3085d6',
                        //     cancelButtonColor: '#d33',
                        //     confirmButtonText: 'I understand'
                        //     }).then((result) => {
                        //     if (result.value) {
                        //         whensubmit = false;
                        //         $("#form").submit();
                        //     }
                        // })
    
                        return false;
                    // }else{
                    //     // alert("Please save new time input before saving the form!");
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Unable to submit form',
                    //         text: 'Please save new time input before submitting the form!'
                    //     })
                    //     // $("#inputstart-0").prop('required',true);
                    //     // $("#inputend-0").prop('required',true);
                    //     // $("#inputremark-0").prop('required',true);
                    //     whensubmit = false;
                    //     return false;
                    // }
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Unable to submit form',
                    text: 'Please add time to your claim'
                })
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
    });   

    $("#costc").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
    });    
    
    $("#compn").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
    });    

    $("#type").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
    });    
    $("#orderno").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
    });    
    $("#networkh").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
    });    
    $("#networkn").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
    });    
    $("#approvern").change(function(){
        $("#formtype").val("save");
        $("#form").submit();
    });    

    @if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif
</script>
@stop
           