@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<h1>Claim Verification  Report</h1>
<div class="panel panel-default">
    <div class="panel-body">
        {{--@if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif--}}
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered tbot">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Reference No</th>
                        <th>OT Date</th>
                        <th>Start OT</th>
                        <th>End OT</th>
                        <th>Total Hours/Minutes</th>
                        <th>Day Type</th>
                        <th>Charge Code</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                        <tr>
                            <td></td>
                            <td><a href="" id="a-{{++$no}}" style="font-weight: bold; color: #143A8C" data-id="{{$singleuser->id}}">{{ $singleuser->refno }}</a></td>
                            <td>{{ date("d.m.Y", strtotime($singleuser->date)) }}</td>
                            <td>@foreach($singleuser->detail as $details){{date('Hi', strtotime($details->start_time)) }}<br>@endforeach</td>
                            <td>@foreach($singleuser->detail as $details){{ date('Hi', strtotime($details->end_time))}}<br>@endforeach</td>
                            <td>{{ $singleuser->total_hour }}h/{{ $singleuser->total_minute }}m</td>
                            <td>{{$singleuser->daytype->description}}</td> 
                            <td>
                                @if($singleuser->charge_type!=null)
                                    {{ $singleuser->charge_type }}
                                @else
                                    N/A
                                @endif
                            </td> 
                            <td>@foreach($singleuser->detail as $details){{$details->in_latitude}} {{$details->in_longitude}}<br>@endforeach</td> 
                            <td 
                                @foreach($singleuser->log as $logs) 
                                    @if(strpos($logs->message,"Queried")!==false) 
                                        @php($query = $logs->message) 
                                    @endif 
                                @endforeach 
                                @if(($singleuser->status=="Q2")||($singleuser->status=="Q1"))
                                    title = "{{str_replace('"', '', str_replace('Queried with message: "', '', $query))}}"
                                @endif> 
                                @if(($singleuser->status=="D2")||($singleuser->status=="D1"))
                                    Draft <p style="color: red">Due: {{$singleuser->date_expiry}}</p> 
                                @elseif(($singleuser->status=="Q2")||($singleuser->status=="Q1"))
                                    @php($query = "") <p>Query</p>
                                @elseif($singleuser->status=="PA")
                                    <p>Pending Approval</p>
                                @elseif($singleuser->status=="PV")
                                    <p>Pending Verification</p>
                                @elseif($singleuser->status=="A")
                                    <p>Approved</p>
                                @else 
                                    {{ $singleuser->status}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <form action="{{route('ot.detail')}}" method="POST" class="hidden" id="form">
            @csrf
            <input type="text" class="hidden" name="detailid" id="detailid" value="" required>
            <input type="text" class="hidden" name="type" value="report" required>
        </form>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">

// alert("{{count($otlist)}}");

$(document).ready(function() {
    var t = $('#tOTList').DataTable({
        "responsive": "true",
        "order" : [[0, "desc"]],
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});

@if(session()->has('feedback'))
    Swal.fire({
        title: "{{session()->get('feedback_title')}}",
        html: "{{session()->get('feedback_text')}}",
        confirmButtonText: 'DONE'
    })
@endif

function yes(i){
    return function(){
        var id = $("#a-"+i).data('id');
        $("#detailid").val(id);
        // alert($("#inputid").val());
        $("#form").submit();
        return false;
    }
}

for(i=1; i<{{count($otlist)+1}}; i++){
    $("#a-"+i).on("click", yes(i));
}

</script>
@stop