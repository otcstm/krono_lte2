@extends('adminlte::page')

@section('title', 'Overtime List')

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
    .panel{
        border: 0;
        border-radius: 0;
    }
    .panel-in, .panel-group{
        border: 0;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        border-radius: 0 !important;
    }
    .panel-in{
        border-bottom:1px solid #A9A9A9;
    }
    .panel-main{
        border: 1px solid #ddd !important;
        border-radius: 4px;
    }
    .panel-head{
        border-bottom:1px solid #A9A9A9 !important;
        background-color: #f5f5f5;
    }
</style>
<div class="panel panel-main panel-default">
    <div class="panel-heading panel-primary">OT Approval/Verification @if(session()->get('mass'))(Mass Action)@endif</div>
    <div class="panel-body">
        @if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif
        <form action="{{route('ot.action')}}" method="POST" style="display:inline"> 
            @csrf                   
            @foreach($otlist as $no=>$singleuser)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <input type="text" class="form-control hidden" id="inputid" name="inputid[]" value="{{$singleuser->id}}" required>
                    <thead>
                        <tr>
                            @if(session()->get('mass'))<th>No</th>@endif
                            <th>Reference No</th>
                            <th>Date time</th>
                            <th>Duration</th>
                            <th>Charge</th>
                            <th>Amount (Estimated)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @if(session()->get('mass'))<td>{{++$no}}</td>@endif
                            <td><a data-target="#collapsible-{{$no}}" data-toggle="collapse">{{ $singleuser->refno }}</a><p>{{ $singleuser->name->name }}</p></td>
                            <td>{{ $singleuser->date }} @foreach($singleuser->detail as $details)<br>{{date('H:i', strtotime($details->start_time)) }} - {{ date('H:i', strtotime($details->end_time))}}@endforeach</td>
                            <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                            <td></td>
                            <td></td>
                            <td>{{ $singleuser->status }}</td>
                            <td>
                                <select name="inputaction[]" required>
                                    <option hidden disabled selected value="">Select Action</option>
                                    @if($singleuser->status=="Pending Verification")<option value="Pending Approval">Verify</option>
                                    @elseif($singleuser->status=="Pending Approval")<option value="Approved">Approve</option>
                                    @endif
                                    <option value="Query">Query</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="@if(session()->get('mass')) 8 @else 7 @endif" style="padding: 0">
                                <div id="collapsible-{{$no}}" class="collapse panel panel-default" style="margin-bottom: 0 !important"> 
                                    <div class="panel-group" id="accordion-{{$no}}">
                                        <div class="panel panel-in">
                                            <a style="color: #000" data-toggle="collapse" data-parent="#accordion-{{$no}}" href="#collapse-{{$no}}-1">
                                                <div class="panel-heading panel-head">
                                                    <h4 class="panel-title">OT Information</h4>
                                                </div>
                                            </a>
                                            <div id="collapse-{{$no}}-1" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <p>Reference No: {{$singleuser->date}}</p>
                                                            <p>Reference No: {{$singleuser->refno}}</p>
                                                            <p>State Calendar: </p>
                                                            <p>Justification: {{$singleuser->justification}}
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <p>Status: {{$singleuser->status}}</p>
                                                            <p>Verifier: {{$singleuser->verifier->name}}</p>
                                                            <p>Approver: {{$singleuser->approver->name}}</p>
                                                            <p>Charging type: {{$singleuser->charge_type}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-in">
                                            <a style="color: #000" data-toggle="collapse" data-parent="#accordion-{{$no}}" href="#collapse-{{$no}}-2">
                                                <div class="panel-heading panel-head">
                                                    <h4 class="panel-title">OT Time List</h4>
                                                </div>
                                            </a>
                                            <div id="collapse-{{$no}}-2" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="info">
                                                                <th width="2%">No</th>
                                                                <th width="20%">Clock In/Out</th>
                                                                <th width="20%">Start/End Time</th>
                                                                <th width="8%">Total Time</th>
                                                                <th width="40%">Justification</th>
                                                            </tr>
                                                        <thead>
                                                        <tbody>
                                                            @foreach($singleuser->detail as $n=>$details)
                                                            <tr>
                                                                <td>{{++$n }}</td>
                                                                <td></td>
                                                                <td>{{ date('H:i', strtotime($details->start_time)) }} - {{ date('H:i', strtotime($details->end_time)) }}</td>
                                                                <td>{{ $details->hour }}h {{ $details->minute }}m</td>
                                                                <td>{{ $details->justification }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-in">
                                            <a style="color: #000" data-toggle="collapse" data-parent="#accordion-{{$no}}" href="#collapse-{{$no}}-3">
                                                <div class="panel-heading panel-head">
                                                    <h4 class="panel-title">OT Charging</h4>
                                                </div>
                                            </a>
                                            <div id="collapse-{{$no}}-3" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    oblo oblo oblo 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-in">
                                            <a style="color: #000" data-toggle="collapse" data-parent="#accordion-{{$no}}" href="#collapse-{{$no}}-4">
                                                <div class="panel-heading panel-head">
                                                    <h4 class="panel-title">OT Action</h4>
                                                </div>
                                            </a>
                                            <div id="collapse-{{$no}}-4" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="info">
                                                                <th width="10%">Date</th>
                                                                <th width="15%">Action</th>
                                                                <th>Message</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($singleuser->log as $logs)
                                                            <tr>
                                                                <td>{{$logs->created_at}}</td>
                                                                <td>{{$logs->name->name}}</td>
                                                                <td>{{$logs->message}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr style="text-align:center;">
                            <td colspan="@if(session()->get('mass')) 8 @else 7 @endif">
                                <span style="position: relative; top: -30px;"><b>Justification: </b></span>
                                <textarea rows = "2" cols = "100" type="text"  id="inputremark" name="inputremark[]" value="" placeholder="Write justification" style="resize: none; display: inline" required></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endforeach
            <div id="submitbtn" class="text-center" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')">
                <button type="submit" class="btn btn-primary">SUBMIT</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        // $('#tOTList').DataTable({
        //     "responsive": "true",
        //     "order" : [[1, "asc"]],
        // });
    });
</script>
@stop