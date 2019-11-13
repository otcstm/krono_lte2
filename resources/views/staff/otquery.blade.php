@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
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
                            <td>{{ $singleuser->refno }}<p>{{ $singleuser->name->name }}</p></td>
                            <td>{{ $singleuser->date }} @foreach($singleuser->detail as $details)<p>{{date('H:i', strtotime($details->start_time)) }} - {{ date('H:i', strtotime($details->end_time))}}</p>@endforeach</td>
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
                                    <option value="Draft (Complete)">Query</option>
                                </select>
                            </td>
                        </tr>
                        <tr style="text-align:center;"><td colspan="@if(session()->get('mass')) 8 @else 7 @endif"><span style="position: relative; top: -30px;"><b>Justification: </b></span><textarea rows = "2" cols = "100" type="text"  id="inputremark" name="inputremark[]" value="" placeholder="Write justification" style="resize: none; display: inline" required></textarea></td></tr>
                        <tr style="text-align:center;">
                            <td colspan="@if(session()->get('mass')) 8 @else 7 @endif">
                                <button type="button" class="btn btn-primary" id="collapse-{{$no}}">SHOW DETAILS</button>
                                <div id="collapsible-{{$no}}" class="collapse">
                                    <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                    commodo consequat.
                                    </div>
                                </div>
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

    function docollapse(i){
        return function(){
            $('#collapsible-'+i).collapse('toggle');
            for(j=1; j<{{count($otlist)+1}}; j++){
                if(j!=i){
                    $('#collapse-'+j).text('SHOW DETAILS')
                    $('#collapsible-'+j).collapse('hide');
                }
            }
            if($('#collapse-'+i).text()=='SHOW DETAILS'){
                $('#collapse-'+i).text('HIDE DETAILS');
            }else{
                $('#collapse-'+i).text('SHOW DETAILS');
            }
        }
    }

    for(i=1; i<{{count($otlist)+1}}; i++){
        $("#collapse-"+i).on('click',docollapse(i));
    }
    
</script>
@stop