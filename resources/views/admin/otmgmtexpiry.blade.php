@extends('adminlte::page')

@section('title', 'Overtime Management (Expiry)')

@section('content')

@php($n = count($oe)-1)
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Overtime Management (Expiry) - {{$oe->get(0)->companyid->company_descr}} ({{$oe->get(0)->region}})</div>
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
                        <th>OT Status</th>
                        <th>No of Month</th>
                        <th>Base Date</th>
                        <th>Action After</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($oe as $i => $singleuser)
                    <tr>
                        <td></td>
                        <td>
                            @if($singleuser->otstatus=="D")
                                Draft
                            @elseif($singleuser->otstatus=="Q")
                                Query
                            @elseif($singleuser->otstatus=="PA")
                                Pending Approval
                            @elseif($singleuser->otstatus=="PV")
                                Pending Verification
                            @endif
                        </td>
                        <td>{{ $singleuser->noofmonth }}</td>
                        <td>{{ $singleuser->based_date }}</td>
                        <td>{{ $singleuser->action_after }}</td>
                        <td>{{ $singleuser->start_date }}</td>
                        <td>{{ $singleuser->end_date }}</td>
                        <td>{{ $singleuser->status }}</td>
                        <td>
                            @if(strtotime($singleuser->start_date)>strtotime(date("Y-m-d")))
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit" data-id="{{$singleuser['id']}}" data-noofmonth="{{$singleuser['noofmonth']}}" data-action_after="{{$singleuser['action_after']}}"data-based_date="{{$singleuser['based_date']}}" data-otstatus="{{$singleuser['otstatus']}}" data-start_date="{{$singleuser['start_date']}}"><i class="fas fa-pencil-alt"></i></button>
                                @if($singleuser->end_date == '9999-12-31')
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete" data-id="{{$singleuser['id']}}" data-noofmonth="{{$singleuser['noofmonth']}}" data-action_after="{{$singleuser['action_after']}}"data-based_date="{{$singleuser['based_date']}}" data-otstatus="{{$singleuser['otstatus']}}" data-start_date="{{$singleuser['start_date']}}"><i class="fas fa-trash"></i></button>
                                @endif
                            @endif
                            @if(strtotime($singleuser->end_date)>strtotime(date("Y-m-d")))
                                <form action="{{ route('oe.active') }}" method="POST">
                                    @csrf
                                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$singleuser->id}}" required>
                                    <input type="text" class="hidden" id="formtype" name="formtype" value="expiry" required>
                                    <input type="text" class="hidden" id="inputcompany" name="inputcompany" value="{{$oe->get($n)->company_id}}" required>
                                    <input type="text" class="hidden" id="inputregion" name="inputregion" value="{{$oe->get($n)->region}}" required>
                                    <button type="submit" class="btn 
                                        @if($singleuser->status=='ACTIVE')
                                            btn-warning" style="padding: 0 5px">
                                            DEACTIVATE
                                        @else
                                            btn-primary" style="padding: 0 5px">
                                            ACTIVATE
                                        @endif
                                    </button>
                                </form>
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
            <form action="{{route('oe.expirystore')}}" method="POST" onsubmit="return update()">
                @csrf
                <div class="col-lg-6">
                    <input type="text" class="hidden" id="formtype" name="formtype" value="expiry" required>
                    <input type="text" class="hidden" id="inputcompany" name="inputcompany" value="{{$oe->get($n)->company_id}}" required>
                    <input type="text" class="hidden" id="inputregion" name="inputregion" value="{{$oe->get($n)->region}}" required>
                    <div class="row">
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputstatus">Overtime Status:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <select id="inputstatus" name="inputstatus" class="form-control onchange" required>
                                <option hidden disabled value="" selected>Select Status</option>
                                <option value="D">Draft</option>
                                <option value="Q">Query</option>
                                <option value="PA">Pending Approval</option>
                                <option value="PV">Pending Verification</option>
                            </select>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputmonth">No. per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input class="form-control onchange" type="number" id="inputmonth" name="inputmonth" max="744" min="0" value="" required disabled>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputbasedate">Based Date:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <select id="inputbasedate" name="inputbasedate" class="form-control onchange" required disabled>
                                <option hidden disabled value="" selected>Select Based Date</option>
                                <option value="Request Date">Request Date</option>
                                <option value="Overtime Date">Overtime Date</option>
                                <option value="Submit to Approver Date">Submit to Approver Date</option>
                                <option value="Submit to Verifier Date">Submit to Verifier Date</option>
                                <option value="Query Date">Query Date</option>
                            </select>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputaction">Action After:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                        <select id="inputaction" name="inputaction" class="form-control onchange" required disabled>
                                <option hidden disabled value="" selected>Select Action After</option>
                                <option value="Delete">Delete from Database</option>
                                <option value="Archive">Archive with Expired Date</option>
                            </select>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputdate">Effective Date:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="date" class="form-control" id="inputdate" name="inputdate" min="" value="" required disabled>
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
                    <input type="text" class="hidden" id="formtype" name="formtype" value="expiry" required>
                    <input type="text" class="hidden" id="inputcompany" name="inputcompany" value="{{$oe->get($n)->company_id}}" required>
                    <input type="text" class="hidden" id="inputregion" name="inputregion" value="{{$oe->get($n)->region}}" required>
                    <div class="row">
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputestatus">Overtime Status:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <select id="inputestatus" name="inputestatus" class="form-control onchange" required disabled>
                                <option value="D">Draft</option>
                                <option value="Q">Query</option>
                                <option value="PA">Pending Approval</option>
                                <option value="PV">Pending Verification</option>
                            </select>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputemonth">No. per Month:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input class="form-control onchange" type="number" id="inputemonth" name="inputemonth" max="744" min="0" value="" required>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputebasedate">Based Date:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <select id="inputebasedate" name="inputebasedate" class="form-control onchange" required>
                                <option value="Request Date">Request Date</option>
                                <option value="Overtime Date">Overtime Date</option>
                                <option value="Submit to Approver Date">Submit to Approver Date</option>
                                <option value="Submit to Verifier Date">Submit to Verifier Date</option>
                                <option value="Query Date">Query Date</option>
                            </select>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputeaction">Action After:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                        <select id="inputeaction" name="inputeaction" class="form-control onchange" required>
                                <option value="Delete">Delete from Database</option>
                                <option value="Archive">Archive with Expired Date</option>
                            </select>
                        </div>
                        <div class="col-lg-3" style="margin-top: 5px">
                            <label for="inputedate">Effective Date:</label>
                        </div>
                        <div class="col-lg-9" style="margin-top: 5px">
                            <input type="date" class="form-control" id="inputedate" name="inputedate" min="" value="" required>
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
                <p><b>Overtime Status:</b> <span id="otstatus"></span></p>
                <p><b>No. per Month:</b> <span id="noofmonth"></span></p>
                <p><b>Based Date:</b> <span id="based_date"></span></p>
                <p><b>Action After:</b> <span id="action_after"></span></p>
                <p><b>Effective Date:</b>  <span id="start_date"></span></p>
                <form action="{{ route('oe.expirydelete') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <input type="text" class="hidden" id="formtype" name="formtype" value="expiry" required>
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
    $("#inputdate").attr("min", y+"-"+m+"-"+d);
}

$("#inputstatus").change(function(){
    status = $("#inputstatus").val();
    const url='{{ route("oe.getexpiry", [], false)}}';
    $.ajax({
    url: url+"?region="+"{{$oe->get($n)->region}}"+"&company="+"{{$oe->get($n)->company_id}}"+"&status="+status,
    type: "GET",
    success: function(resp) {
        $('input[name=inputmonth]').attr("disabled", false);
        $('select[name=inputbasedate]').attr("disabled", false);
        $('select[name=inputaction]').attr("disabled", false);
        $('input[name=inputdate]').attr("disabled", false);
        if((Date.parse(y+"-"+m+"-"+d)>=Date.parse(resp.min))||(resp.min==null)){
            $('input[name=inputdate]').attr("min",  y+"-"+m+"-"+d);
            $('input[name=inputdate]').val(y+"-"+m+"-"+d);
        }else{
            $('input[name=inputdate]').attr("min", resp.min);
            $('input[name=inputdate]').val(resp.min);
        }
        if(resp.min!=null){
            $('input[name=inputmonth]').val(resp.mon);
            $('select[name=inputbasedate]').val(resp.bd);
            $('select[name=inputaction]').val(resp.aa);
        }else{
            $('input[name=inputmonth]').val('');
            $('select[name=inputbasedate]').val('');
            $('select[name=inputaction]').val('');
        }
        change = true;
    },
        error: function(err) {
            // respjson.forEach(myFunction);
        }
    });
});

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
    var noofmonth = $(e.relatedTarget).data('noofmonth');
    var action_after = $(e.relatedTarget).data('action_after');
    var based_date = $(e.relatedTarget).data('based_date');
    var otstatus = $(e.relatedTarget).data('otstatus');
    var start_date = $(e.relatedTarget).data('start_date');

    const url='{{ route("oe.expirygetlast", [], false)}}';
    $.ajax({
    url: url+"?region="+"{{$oe->get($n)->region}}"+"&company="+"{{$oe->get($n)->company_id}}"+"&sd="+start_date,
    type: "GET",
    success: function(resp) {
        $('input[name=inputedate]').attr("min", resp.min);
        $('input[name=inputedate]').attr("max", resp.max);
    },
        error: function(err) {
            // respjson.forEach(myFunction);
        }
    });

    
    $('input[name=inputid]').val(id);
    $('input[name=inputemonth]').val(noofmonth);
    $('select[name=inputestatus]').val(otstatus);
    $('select[name=inputeaction]').val(action_after);
    $('select[name=inputebasedate]').val(based_date);
    $('input[name=inputedate]').val(start_date);
    $('input[name=inputedate]').attr("min", );
});

$('#delete').on('show.bs.modal', function(e) {
    var id = $(e.relatedTarget).data('id');
    var noofmonth = $(e.relatedTarget).data('noofmonth');
    var action_after = $(e.relatedTarget).data('action_after');
    var based_date = $(e.relatedTarget).data('based_date');
    var otstatus = $(e.relatedTarget).data('otstatus');
    var start_date = $(e.relatedTarget).data('start_date');
    if(otstatus=="D"){
        otstatus = "Draft";
    }else if(otstatus=="Q"){
        otstatus = "Query";
    }else if(otstatus=="PA"){
        otstatus = "Pending Approval";
    }else if(otstatus=="PV"){
        otstatus = "Pending Verification";
    }
    
    $('input[name=inputid]').val(id);
    $('#noofmonth').text(noofmonth);
    $('#action_after').text(action_after);
    $('#based_date').text(based_date);
    $('#otstatus').text(otstatus);
    $('#start_date').text(start_date);
});
</script>
@stop