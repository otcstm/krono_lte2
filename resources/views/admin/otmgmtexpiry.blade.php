@extends('adminlte::page')

@section('title', 'Overtime Management (Expiry)')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Overtime Management (Expiry)</div>
    <div class="panel-body">
        @if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif
        <div class="table-responsive">
            <table id="tRoleList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>No of Month</th>
                        <th>Base Date</th>
                        <th>Action After</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <form id="form" action="{{route('oe.active')}}" method="POST">
                        @csrf
                        <input type="text" class="hidden" id="inputid" name="inputid" value="" required>
                        <input type="text" class="hidden" id="inputmonth" name="inputmonth" value="" required>
                        <input type="text" class="hidden" id="inputbase" name="inputbase" value="" required>
                        <input type="text" class="hidden" id="inputaction" name="inputaction" value="" required>
                        <input type="text" class="hidden" id="inputactive" name="inputactive" value="" required>
                        @foreach($oe as $no=>$singleuser)
                        <tr>
                            <td></td>
                            <td>
                                @if($singleuser->status=="D")
                                    Draft
                                @elseif($singleuser->status=="Q")
                                    Query
                                @elseif($singleuser->status=="PA")
                                    Pending Approval
                                @elseif($singleuser->status=="PV")
                                    Pending Verification
                                @endif
                            </td>
                            <td>
                                @if($singleuser->end_date=="9999-12-31")
                                    <input type="number" id="month-{{$no}}" min="1" data-id="{{$singleuser->id}}" value="{{ $singleuser->noofmonth }}">
                                @else
                                    {{ $singleuser->noofmonth }}
                                @endif
                            </td>
                            <td>
                                @if($singleuser->end_date=="9999-12-31")
                                    <select id="base-{{$no}}" data-id="{{$singleuser->id}}">
                                        <option value="Request Date"
                                            @if($singleuser->based_date=="Request Date")
                                                selected
                                            @endif
                                        >Request Date</option>
                                        <option value="OT Date"
                                            @if($singleuser->based_date=="OT Date")
                                                selected
                                            @endif
                                        >OT Date</option>
                                        <option value="Submit to Verifier Date"
                                            @if($singleuser->based_date=="Submit to Verifier Date")
                                                selected
                                            @endif
                                        >Submit to Verifier Date</option>
                                        <option value="Submit to Approver Date"
                                            @if($singleuser->based_date=="Submit to Approver Date")
                                                selected
                                            @endif
                                        >Submit to Approver Date</option>
                                        <option value="Query Date"
                                            @if($singleuser->based_date=="Query Date")
                                                selected
                                            @endif
                                        >Query Date</option>
                                @else
                                    {{ $singleuser->based_date }}
                                @endif
                            </td>
                            <td>
                                @if($singleuser->end_date=="9999-12-31")
                                    <select id="after-{{$no}}" data-id="{{$singleuser->id}}">
                                        <option value="Delete"
                                            @if($singleuser->action_after=="Delete")
                                                selected
                                            @endif
                                        >Delete</option>
                                        <option value="Archieve"
                                            @if($singleuser->action_after=="Archieve")
                                                selected
                                            @endif
                                        >Archieve</option>
                                    </select>
                                @else
                                    {{ $singleuser->action_after }}
                                @endif
                            </td>
                            <td>{{ $singleuser->start_date }}</td>
                            <td>{{ $singleuser->end_date }}</td>
                            <td>
                                @if($singleuser->end_date=="9999-12-31")
                                    <input type="checkbox" id="checkbox-{{$no}}"  data-id="{{$singleuser->id}}"
                                        @if($singleuser->active=="X")
                                            checked
                                        @endif
                                    >
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </form>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <form action="{{route('oe.store')}}" method="POST" onsubmit="return update()">
                    @csrf
                    <input type="text" class="hidden" id="inputcompany" name="inputcompany" value="{{$oe->get(0)->company_id}}" required>
                    <input type="text" class="hidden" id="inputregion" name="inputregion" value="{{$oe->get(0)->region}}" required>
                    <div class="row">
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputsalary">Salary Cap:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputsalary" name="inputsalary" min="0" value="{{$oe->get(0)->salary_cap}}" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputhourpm">Hour per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputhourpm" name="inputhourpm" max="744" min="0" value="{{$oe->get(0)->hourpermonth}}" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputhourpd">Hour per Day:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputhourpd" name="inputhourpd" min="0" max="24" value="{{$oe->get(0)->hourperday}}"required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputdaypm">Day per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputdaypm" name="inputdaypm" min="0" max="31" value="{{$oe->get(0)->daypermonth}}" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputdate">Effective Date:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="date" class="onchange" id="inputdate" name="inputdate" min="@php($n = count($oe)-1){{$oe->get($n)->start_date}}" value="{{$oe->get($n)->start_date}}" required>
                        </div>                    
                    </div>
                </div>
            </div>
            <div class="text-center" style="margin-top: 25px">
                <a href="{{ route('oe.otm') }}"><button type="button" class="btn btn-warning">RETURN</button></a>
                <button type="submit" class="btn btn-primary">UPDATE</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    // var t = $('#tRoleList').DataTable({
    //     "responsive": "true",
    //     "order" : [[0, "desc"]],
    //     // "searching": false,
    //     // "bSort": false
    // });

    // t.on( 'order.dt search.dt', function () {
    //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
    //         cell.innerHTML = i+1;
    //     } );
    // } ).draw();

    var t = $('#tRoleList').DataTable({
        "responsive": "true",
        "order" : [[5, "desc"]],
        "searching": false,
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});

var change = true;
var today = new Date();
var m = today.getMonth()+1;
var y = today.getFullYear();
var d = today.getDate().toString();
if(m < 10){
        m = "0"+m;
}
while(d.length<2){
    d = "0"+d;
}
// alert(y+"-"+m+"-"+d);
// alert({{$oe->get($n)->start_date}});
// alert(Date.parse({{date('Y-m-d', strtotime($oe->get($n)->start_date))}}));
// alert("{{date('Y-m-d', strtotime($oe->get(0)->start_date))}}");
if(Date.parse(y+"-"+m+"-"+d)>=Date.parse("{{date('Y-m-d', strtotime($oe->get($n)->start_date))}}")){
    $("#inputdate").val(y+"-"+m+"-"+d);
}
$("#inputdate").attr("min", y+"-"+m+"-"+d);


$(".onchange").change(function(){
    change = false;
});

function update(){
    if(change){
        return false;
    }
}
</script>
@stop