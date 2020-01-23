@extends('adminlte::page')

@section('title', 'Overtime Management (Eligibility)')

@section('content')

@php($n = count($oe)-1)
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
                        <th>Start Date</th>
                        <th>End Date</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($oe as $i => $singleuser)
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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit" data-id="{{$singleuser['id']}}" data-salary_cap="{{$singleuser['salary_cap']}}" data-hourpermonth="{{$singleuser['hourpermonth']}}"data-hourperday="{{$singleuser['hourperday']}}" data-daypermonth="{{$singleuser['daypermonth']}}" data-start_date="{{$singleuser['start_date']}}"><i class="fas fa-pencil-alt"></i></button>
                                @if($i == $n)
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete"><i class="fas fa-trash"></i></button>

                                @endif
                            @endif
                        </td>
                    </tr>
                    @php(++$i)
                    @endforeach
                </tbody>
            </table>
        </div>
        <h4><b>ADD NEW</b></h4>
        <div class="row">
            <div class="col-lg-6">
                <form action="{{route('oe.eligiblestore')}}" method="POST" onsubmit="return update()">
                    @csrf
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
                            <input type="date" id="inputdate" name="inputdate" min="{{date('Y-m-d', strtotime($oe->get($n)->start_date . '+1 days'))}}" value="{{date('Y-m-d', strtotime($oe->get($n)->start_date . '+1 days'))}}" required>
                        </div>                    
                    </div>
                </div>
            </div>
            <div class="text-center" style="margin-top: 25px">
                <a href="{{ route('oe.otm') }}"><button type="button" class="btn btn-warning">RETURN</button></a>
                <button type="submit" class="btn btn-primary">ADD NEW</button>
            </div>
        </form>
    </div>
</div>

<div id="edit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Configuration</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('oe.eligibleupdate')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <input type="text" class="hidden" id="formtype" name="formtype" value="eligibility" required>
                    <input type="text" class="hidden" id="inputcompany" name="inputcompany" value="{{$oe->get($n)->company_id}}" required>
                    <input type="text" class="hidden" id="inputregion" name="inputregion" value="{{$oe->get($n)->region}}" required>
                    <div class="row">
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputesalary">Salary Cap:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" id="inputesalary" name="inputesalary" min="0" value="" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputehourpm">Hour per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" id="inputehourpm" name="inputehourpm" max="744" min="0" value="" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputehourpd">Hour per Day:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" id="inputehourpd" name="inputehourpd" min="0" max="24" value=""required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputedaypm">Day per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="number" id="inputedaypm" name="inputedaypm" min="0" max="31" value="" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputedate">Effective Date:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="date" id="inputedate" name="inputedate" min="" value="" required>
                        </div>                    
                    </div>
                    <br>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">UPDATE</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="modal-close" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div id="delete" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Configuration</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete this configuration?<p>
                <p><b>Salary Cap:</b> {{$oe->get($n)->salary_cap}}</p>
                <p><b>Hour per Month:</b> {{$oe->get($n)->hourpermonth}}</p>
                <p><b>Hour per Day:</b> {{$oe->get($n)->hourperday}}</p>
                <p><b>Day per Month:</b> {{$oe->get($n)->daypermonth}}</p>
                <p><b>Effective Date:</b> {{$oe->get($n)->start_date}}</p>
                <form action="{{ route('oe.eligibledelete') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$oe->get($n)->id}}" required>
                    <input type="text" class="hidden" id="formtype" name="formtype" value="eligibility" required>
                    <input type="text" class="hidden" id="inputcompany" name="inputcompany" value="{{$oe->get($n)->company_id}}" required>
                    <input type="text" class="hidden" id="inputregion" name="inputregion" value="{{$oe->get($n)->region}}" required>
                    <button type="submit" class="btn btn-danger">DELETE</button>
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
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
if(Date.parse(y+"-"+m+"-"+d)>=Date.parse("{{date('Y-m-d', strtotime($oe->get($n)->start_date . '+1 days'))}}")){
    $("#inputdate").val(y+"-"+m+"-"+d);
    $("#inputdate").attr("min", y+"-"+m+"-"+d);
}

$(".onchange").change(function(){
    change = false;
});

function update(){
    if(change){
        Swal.fire(
            'Failed to create new!',
            'Input data cannot be the same as current entry',
            'error'
        )
        return false;
    }
}

$('#edit').on('show.bs.modal', function(e) {
    
    var id = $(e.relatedTarget).data('id');
    var salary_cap = $(e.relatedTarget).data('salary_cap');
    var hourpermonth = $(e.relatedTarget).data('hourpermonth');
    var hourperday = $(e.relatedTarget).data('hourperday');
    var daypermonth = $(e.relatedTarget).data('daypermonth');
    var start_date = $(e.relatedTarget).data('start_date');

    const url='{{ route("oe.eligiblegetlast", [], false)}}';
        $.ajax({
        url: url+"?region="+"{{$oe->get($n)->region}}"+"&company="+"{{$oe->get($n)->company_id}}"+"&sd="+start_date,
        type: "GET",
        success: function(resp) {
            if(Date.parse(y+"-"+m+"-"+d)>=Date.parse(resp.min)){
                $('input[name=inputedate]').attr("min",  y+"-"+m+"-"+d);
            }else{
                $('input[name=inputedate]').attr("min", resp.min);
            }
            $('input[name=inputedate]').attr("max", resp.max);
        },
        error: function(err) {
            // respjson.forEach(myFunction);
        }
    });

    
    $('input[name=inputid]').val(id);
    $('input[name=inputesalary]').val(salary_cap);
    $('input[name=inputehourpm]').val(hourpermonth);
    $('input[name=inputehourpd]').val(hourperday);
    $('input[name=inputedaypm]').val(daypermonth);
    $('input[name=inputedate]').val(start_date);
    $('input[name=inputedate]').attr("min", );
});
</script>
@stop