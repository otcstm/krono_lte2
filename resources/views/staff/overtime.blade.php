@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT List</div>
    <div class="panel-body">
        <div class="text-right" style="margin-bottom: 15px">
            <form action="{{route('ot.formnew')}}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-primary">CREATE NEW CLAIM</button>
            </form>
        </div>
        @if(session()->has('feedback'))
        <div class="alert alert-{{session()->get('feedback_type')}} alert-dismissible" id="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{session()->get('feedback_text')}}
        </div>
        @endif
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Reference No</th>
                        <th>Date time</th>
                        <th>Duration</th>
                        <th>Estimated Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                        <tr>
                            <td>@if(($singleuser->status=="D2")||($singleuser->status=="Q2"))<input type="checkbox" id="checkbox-{{$no}}" value="{{$singleuser->id}}"> @endif</td>
                            <td></td>
                            <td>{{ $singleuser->refno }}</td>
                            <td>{{ $singleuser->date }} @foreach($singleuser->detail as $details)<br>{{date('H:i', strtotime($details->start_time)) }} - {{ date('H:i', strtotime($details->end_time))}}@endforeach</td>
                            <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                            <td>RM{{$singleuser->amount}}</td>
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
                            <td>
                                @if(in_array($singleuser->status, $array = array("D1", "D2", "Q2", "Q1")))
                                    <form action="{{route('ot.update')}}" method="POST" style="display:inline">
                                        @csrf
                                        <input type="text" class="hidden" id="inputid" name="inputid" value="{{$singleuser->id}}" required>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button>
                                    </form>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delOT" data-id="{{$singleuser->id}}" data-date="{{$singleuser->date}}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @else
                                <form action="{{route('ot.update')}}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="text" class="hidden" id="inputid" name="inputid" value="{{$singleuser->id}}" required>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="submitbtn" class="text-center" style="display: none">
            <form action="{{route('ot.submit')}}" method="POST" onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')" style="display:inline">
                @csrf
                <input type="text" class="hidden" id="submitid" name="submitid" value="" required>
                <input type="text" class="hidden" id="multi" name="multi" value="yes" required>
                <button type="submit" class="btn btn-primary">SUBMIT</button>
            </form>
        </div>
    </div>
</div>

<div id="delOT" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Claim Time</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete claim for date <span id="deldate"></span>?<p>
                <form action="{{ route('ot.remove') }}" method="POST">
                    @csrf
                    <input type="text" class="hidden" id="delid" name="delid" value="" required>
                    <button type="submit" class="btn btn-primary">DELETE</button>
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
    var t = $('#tOTList').DataTable({
        "responsive": "true",
        "order" : [[0, "desc"]],
    });

    t.on( 'order.dt search.dt', function () {
        t.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});

$('#delOT').on('show.bs.modal', function(e) {
    var id = $(e.relatedTarget).data('id');
    var date = $(e.relatedTarget).data('date');
    $("#delid").val(id);
    $("#deldate").text(date);
})


var show = 0;

function submitval(i){
    return function(){
        if ($('#checkbox-'+i).is(':checked')) {
            $("#submitid").val(function() {
                return this.value + $('#checkbox-'+i).val()+" ";
            });
            show++;
        }else{
            var str = ($('#submitid').val()).replace($('#checkbox-'+i).val()+" ",'');
            $('#submitid').val(str);
            show--;
        }
        if(show>0){
            $('#submitbtn').css("display","block");
        }else{
            $('#submitbtn').css("display","none");
        }
    };
};

for(i=0; i<{{count($otlist)}}; i++) {
    $("#checkbox-"+i).change(submitval(i));
};

</script>
@stop