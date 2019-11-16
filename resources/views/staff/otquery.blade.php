@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<style>
    table.table-bordered{
        border:1px solid #A9A9A9;
    }
    table.table-borderless{
        border:0;
    }
    table.table-bordered > thead > tr > th{
        border:1px solid #A9A9A9;
    }
    table.table-bordered > tbody > tr > td{
        border:1px solid #A9A9A9;
    }
    table.table-borderless > thead > tr > th{
        border:0;
    }
    table.table-borderless > tbody > tr > td{
        border:0;
    }
    .panel{
        border: 0;
        border-radius: 0;
    }
    .panel-in, .panel-in-x, .panel-group{
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
    <div class="panel-heading panel-primary">OT Approval/Verification</div>
    <div class="panel-body">
        @if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif
        @if(count($otlist)!=0)
        <form action="{{route('ot.query')}}" method="POST" style="display:inline"> 
            @csrf    
            <div class="table-responsive">
                <table id="tOTList" class="table table-borderless">
                    <thead style="display: none">
                        <tr>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>      
                        @foreach($otlist as $no=>$singleuser)
                            <tr>
                                <td>
                                    <div class="table-responsive" style="margin-bottom: -25px;">
                                        <table class="table table-bordered">
                                            <input type="text" class="form-control hidden" id="inputid" name="inputid[]" value="{{$singleuser->id}}" required>
                                            <thead>
                                                <tr>
                                                    <th width="2%">No</th>
                                                    <th width="20%">Reference No</th>
                                                    <th width="12%">Date time</th>
                                                    <th width="10%">Duration</th>
                                                    <th width="10%">Charge</th>
                                                    <th width="15%">Amount (Estimated)</th>
                                                    <th width="15%">Status</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{++$no}}</td>
                                                    <td><a data-target="#collapsible-{{$no}}" data-toggle="collapse">{{ $singleuser->refno }}</a><p>{{ $singleuser->name->name }}</p></td>
                                                    <td>{{ $singleuser->date }} @foreach($singleuser->detail as $details)<br>{{date('H:i', strtotime($details->start_time)) }} - {{ date('H:i', strtotime($details->end_time))}}@endforeach</td>
                                                    <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                                                    <td>{{$singleuser->charge_type}}</td>
                                                    <td>RM{{$singleuser->amount}}</td>
                                                    <td>{{ $singleuser->status }}</td>
                                                    <td>
                                                        <select name="inputaction[]" id="action-{{$no}}">
                                                            <option selected value="">Select Action</option>
                                                            <!-- <option hidden disabled selected value="">Select Action</option> -->
                                                            @if($singleuser->status=="Pending Verification")<option value="Pending Approval">Verify</option>
                                                            @elseif($singleuser->status=="Pending Approval")<option value="Approved">Approve</option>
                                                            @endif
                                                            <option value="Query (Complete)">Query</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="8" style="padding: 0">
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
                                                                <div class="panel panel-in-x">
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
                                                <tr style="text-align:center; display: none" id="remark-{{$no}}">
                                                    <td colspan="8">
                                                        <span style="position: relative; top: -30px;"><b>Justification: </b></span>
                                                        <textarea rows = "2" cols = "100" type="text"  id="inputremark-{{$no}}" name="inputremark[]" value="" placeholder="Write justification" style="resize: none; display: inline"></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="submitbtn" class="text-center" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')">
                <button type="submit" class="btn btn-primary">SUBMIT</button>
            </div>
        </form>
        @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="20%">Reference No</th>
                        <th width="12%">Date time</th>
                        <th width="10%">Duration</th>
                        <th width="10%">Charge</th>
                        <th width="15%">Amount (Estimated)</th>
                        <th width="15%">Status</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8"><div class="text-center"><i>Not available</i></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        $('#tOTList').DataTable({
            "responsive": "true",
            // "order" : [[1, "asc"]],
            "searching": false,
            "bSort": false
        });
    });
    
    function remark(i){
        return function(){
            if($("#action-"+i).val()=="Query (Complete)"){
                $("#inputremark-"+i).prop('required',true);
                $('#remark-'+i).css("display", "table-row");
            }else{
                $("#inputremark-"+i).prop('required',false);
                $('#remark-'+i).css("display", "none");
            }
        };
    };

    for (i=1; i<{{count($otlist)+1}}; i++) {
        $("#action-"+i).change(remark(i));
    }
</script>
@stop