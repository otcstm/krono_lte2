@extends('adminlte::page')

@section('title', 'Overtime Management (Eligibility)')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Overtime Management (Eligibility) - {{$oe->get(0)->companyid->company_descr}} ({{$oe->get(0)->region}})</div>
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
                        <th>Salary Cap</th>
                        <th>Hour per Month</th>
                        <th>Hour per Day</th>
                        <th>Day per Month</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($oe as $singleuser)
                    <tr>
                        <td></td>
                        <td>{{ $singleuser->salary_cap }}</td>
                        <td>{{ $singleuser->hourpermonth }}</td>
                        <td>{{ $singleuser->hourperday }}</td>
                        <td>{{ $singleuser->daypermonth }}</td>
                        <td>{{ $singleuser->start_date }}</td>
                        <td>{{ $singleuser->end_date }}</td>
                        <td>
                            @if(strtotime($singleuser->start_date)>strtotime(date("Y-m-d")))
                                yea
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <form action="{{route('oe.store')}}" method="POST" onsubmit="return update()">
                    @csrf
                    @php($n = count($oe)-1)
                    <input type="text" class="hidden" id="formtype" name="formtype" value="eligibility" required>
                    <input type="text" class="hidden" id="inputcompany" name="inputcompany" value="{{$oe->get($n)->company_id}}" required>
                    <input type="text" class="hidden" id="inputregion" name="inputregion" value="{{$oe->get($n)->region}}" required>
                    <div class="row">
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputsalary">Salary Cap:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputsalary" name="inputsalary" min="0" value="{{$oe->get($n)->salary_cap}}" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputhourpm">Hour per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputhourpm" name="inputhourpm" max="744" min="0" value="{{$oe->get($n)->hourpermonth}}" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputhourpd">Hour per Day:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputhourpd" name="inputhourpd" min="0" max="24" value="{{$oe->get($n)->hourperday}}"required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputdaypm">Day per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" class="onchange" id="inputdaypm" name="inputdaypm" min="0" max="31" value="{{$oe->get($n)->daypermonth}}" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputdate">Effective Date:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="date" class="onchange" id="inputdate" name="inputdate" min="{{$oe->get($n)->start_date}}" value="{{$oe->get($n)->start_date}}" required>
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
today.setDate(today.getDate() + 1);
var m = today.getMonth()+1;
var y = today.getFullYear();
var d = today.getDate().toString();

// alert(d);
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