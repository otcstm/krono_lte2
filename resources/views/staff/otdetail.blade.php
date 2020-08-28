@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<h1>Details of Overtime Claim</h1>
<div class="panel panel-default ">
    <div class="panel-body panel-main">
        <p style="margin: 15px 0 35px"><b>Reference No: <span style="color: #143A8C">{{$claim->refno}} ({{$claim->employee_type}})</span></b></p>
        <div class="panel panel-default">
                <div class="panel-heading">
                    <a id="btn-1" data-toggle="collapse" href="#collapse1"><span>Overtime Information</span><i id="fas-1" class="fas fa-sort-up"></i></a>
                </div>
            <div id="collapse1" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">State</div><div class="col-md-8">: <b>{{str_replace(')', '', str_replace('Malaysia (', '', $claim->state->state_descr))}}</b></div>
                                <div class="col-md-4">Day Type</div><div class="col-md-8">: <b>@if($claim->daytype->day_type == "N")
                                    Normal Day
                                @elseif($claim->daytype->day_type == "PH")
                                    Public Holiday
                                @elseif($claim->daytype->day_type == "R")
                                    Rest Day
                                @else
                                    Off Day
                                @endif</b></div>
                                <div class="col-md-4">OT Date</div><div class="col-md-8">: <b>{{date('d.m.Y', strtotime($claim->date))}}</b></div>
                                <div class="col-md-4">Total Hours/Minute</div><div class="col-md-8">: <b>@if($claim->eligible_day==0){{$claim->total_hour}}h {{$claim->total_minute}}m @else @php($total = $claim->eligible_total_hours_minutes*60) {{(int)($total/60)}}h {{$total%60}}m @endif</b></div>
                                {{--<div class="col-md-4">Total Day</div><div class="col-md-8">: <b>{{$claim->eligible_day}}</b></div>--}}
                                <div class="col-md-4">Total Day</div><div class="col-md-8">: <b>@if($claim->daycode->rate==0.5) 0.5 @else 1 @endif</b></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                {{--<div class="col-md-4">Salary Exception</div><div class="col-md-8">: <b>
                                    @if($claim->URecord->ot_salary_exception=="X")
                                        Yes
                                    @else
                                        No
                                    @endif
                                </b></div>--}}
                                <div class="col-md-4">Salary Capping for OT</div><div class="col-md-8">: <b>{{$claim->salary_exception}} </b></div>
                                <div class="col-md-4">Verifier</div><div class="col-md-8">: <b>{{$claim->verifier->name}} @if($claim->verifier_id!="")  ({{$claim->verifier->staff_no}}) @endif</b></div>
                                <div class="col-md-4">Approver</div><div class="col-md-8">: <b>{{$claim->approver->name}} ({{$claim->approver->staff_no}})</b></div>
                                <div class="col-md-4">Estimated Amount</div><div class="col-md-8">: <b>RM {{$claim->amount}}</b></div>
                                <div class="col-md-4">Transaction Code</div><div class="col-md-8">: <b>@if(($claim->eligible_day_code)&&($claim->eligible_total_hours_minutes_code)) {{$claim->eligible_day_code}}, {{$claim->eligible_total_hours_minutes_code}} @elseif($claim->eligible_total_hours_minutes_code) {{$claim->eligible_total_hours_minutes_code}} @else {{$claim->eligible_day_code}} @endif</b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><div class="panel panel-default">
                <div class="panel-heading">
                    <a id="btn-5" data-toggle="collapse" href="#collapse5"><span>Overtime Charge Type</span><i id="fas-5" class="fas fa-sort-down"></i></a>
                </div>
            <div id="collapse5" class="panel-collapse collapse">
                <div class="panel-body"> 
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">Charge Type</div><div class="col-md-8">: <b>{{$claim->charge_type}}</b></div>
                        <div class="col-md-4">Company Code</div><div class="col-md-8">: <b>{{$claim->company_id}}</b></div>
                        <div class="col-md-4">Cost Center</div><div class="col-md-8">: <b>
                            @if(($claim->costcenter==$claim->other_costcenter)||($claim->other_costcenter==""))
                                {{$claim->costcenter}}
                            @else
                                {{$claim->other_costcenter}}
                            @endif
                        </b></div>
                        @if(in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order")))
                            <div class="col-md-4">
                            @if($claim->charge_type=="Project")
                                Project
                            @else
                                Order
                            @endif No
                            </div><div class="col-md-8">: <b>
                            @if($claim->charge_type=="Project")
                                {{$claim->project_no}}
                            @else
                                {{$claim->order_no}}
                            @endif</b></div>
                            <div class="col-md-4">Description</div>
                            @if($claim->charge_type=="Project")
                                <div class="col-md-8">: <b>{{$claim->project->descr}}</b></div>
                            @elseif($claim->charge_type=="Internal Order")
                                <div class="col-md-8">: <b>{{$claim->iorder->descr}}</b></div>
                            @else
                                <div class="col-md-8">: <b>{{$claim->morder->descr}}</b></div>
                            @endif
                        @endif
                    </div>
                </div>
                @if($claim->charge_type=="Project")
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">Network Header</div><div class="col-md-8">: <b>{{$claim->project->network_header}}</b></div>
                            <div class="col-md-4">Network Header Desc</div><div class="col-md-8">: <b>{{$claim->project->network_headerdescr}}</b></div>
                            <div class="col-md-4">Network Activity</div><div class="col-md-8">: <b>{{$claim->project->network_act_no}}</b></div>
                            <div class="col-md-4">Network Activity Desc</div><div class="col-md-8">: <b>{{$claim->project->network_act_descr}}</b></div>
                        </div>
                    </div>
                @endif
            </div>
            </div>
        </div>
        <div class="panel panel-default">
                <div class="panel-heading">
                    <a id="btn-2" data-toggle="collapse" href="#collapse2"><span>Overtime Time List</span><i id="fas-2" class="fas fa-sort-down"></i></a>
                </div>
            <div id="collapse2" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-blue">
                            <thead>    
                                <tr>
                                    <th>No</th>
                                    <th>Start OT</th>
                                    <th>End OT</th>
                                    <th>Hours/Minutes</th>
                                    <th>OT Type</th>
                                    <th>Location</th>
                                    <th>OT Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($claim->detail))
                                    @php($nox = 0)
                                    @php($nod = 0)
                                    @foreach($claim->detail as $no => $details)
                                        @php(++$nox)
                                        @if($details->checked=="Y")
                                        @php(++$nox)
                                        <tr>
                                            <td>{{++$nod}}</td>
                                            <td>{{ date('Hi', strtotime($details->start_time)) }}</td>
                                            <td>{{ date('Hi', strtotime($details->end_time)) }}</td>
                                            <td>{{ $details->hour }}h {{$details->minute}}m</td>
                                            <td>
                                                @if($details->clock_in!="")
                                                    System Input
                                                @else 
                                                    Manual Input
                                                @endif
                                            </td>
                                            <td><a href = "https://www.google.com/maps/search/?api=1&query={{$details->in_latitude}},{{$details->in_longitude}}" target="_blank" style="font-weight: bold; color: #143A8C">{{ $details->in_latitude }} {{ $details->in_longitude }}</a></td>
                                            <td>{{$details->justification}}</td>
                                        </tr>
                                        @else
                                        @php(--$nox)
                                        @endif
                                    @endforeach
                                    @if($nox==0)
                                    <tr>
                                        <td colspan="7"><i>Not Available</i></td> 
                                    </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="7"><i>Not Available</i></td> 
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
                <div class="panel-heading">
                    <a id="btn-3" data-toggle="collapse" href="#collapse3"><span>Overtime Attachment</span><i id="fas-3" class="fas fa-sort-down"></i></a>
                </div>
            <div id="collapse3" class="panel-collapse collapse">
                <div class="panel-body"> 
                    
        @if(count($claim->file)!=0)
                    @foreach($claim->file as $f=>$singlefile)
                        @php(++$f)
                        <a href="{{ asset('storage/'.$singlefile->filename)}}" target="_blank"><img src="{{route('ot.thumbnail', ['tid'=>$singlefile->id], false)}}" title="{{ substr($singlefile->filename, 22)}}"  class="img-fluid img-thumbnails" style="height: 100px; width: 100px; border: 1px solid #A9A9A9; margin-bottom: 10px;"></a>
                        <!-- <a href="{{--route('ot.file', ['tid'=>$singlefile->id], false)--}}" target="_blank"><img src="{{route('ot.thumbnail', ['tid'=>$singlefile->id], false)}}" title="{{ substr($singlefile->filename, 22)}}"  class="img-fluid img-thumbnails" style="height: 100px; width: 100px; border: 1px solid #A9A9A9; margin-bottom: 10px;"></a> -->
                    @endforeach
        @else <p>No attachment</p>            
        @endif

                </div>
            </div>
        </div>
        <div class="panel panel-default">
                <div class="panel-heading">
                    <a id="btn-4" data-toggle="collapse" href="#collapse4"><span>Action Log</span><i id="fas-4" class="fas fa-sort-down"></i></a>
                </div>
            <div id="collapse4" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-blue">
                            <thead>
                                <tr>
                                    <th width="10%">Date</th>
                                    <th width="10%">Time</th>
                                    <th width="25%">Action by</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($claim->log as $singleuser)
                                <tr>
                                    <td>{{date("d.m.Y", strtotime($singleuser->created_at))}}</td>
                                    <td>{{date("Hi", strtotime($singleuser->created_at))}}</td>
                                    <td>{{$singleuser->name->name}}</td>
                                    <td>{{$singleuser->message}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
     </div>

    <div class="panel-footer">
        <div class="text-right">
            @if(session()->get('back')=="ot")
            <a href="{{route('ot.list')}}"><button type="button" class="btn btn-p btn-primary" style="display: inline">BACK</button></a>
            @elseif(session()->get('back')=="verifier")
            <a href="{{route('ot.verify')}}"><button type="button" class="btn btn-p btn-primary" style="display: inline">BACK</button></a>
            @elseif(session()->get('back')=="verifierrept")
            <a href="{{route('ot.verifyrept')}}"><button type="button" class="btn btn-p btn-primary" style="display: inline">BACK</button></a>
            @elseif(session()->get('back')=="approver")
            <a href="{{route('ot.approval')}}"><button type="button" class="btn btn-p btn-primary" style="display: inline">BACK</button></a>
            @elseif(session()->get('back')=="admin")
            <a href="{{route('ot.admin')}}"><button type="button" class="btn btn-p btn-primary" style="display: inline">BACK</button></a>
            @elseif(session()->get('back')=="approverrept")
            <a href="{{route('ot.approvalrept')}}"><button type="button" class="btn btn-p btn-primary" style="display: inline">BACK</button></a>
            @endif
        </div>
    </div>
</div>
@stop

@section('js')

<script type="text/javascript">
    function chang(i){
        return function(){
            if($("#fas-"+i).hasClass("fa-sort-down")){
                $("#fas-"+i).removeClass("fa-sort-down");
                $("#fas-"+i).addClass("fa-sort-up");
            }else{
                $("#fas-"+i).removeClass("fa-sort-up");
                $("#fas-"+i).addClass("fa-sort-down");
            }
        }
    }

    for(i=1; i<6; i++){
        $("#btn-"+i).on("click", chang(i));
    }
</script>
@stop