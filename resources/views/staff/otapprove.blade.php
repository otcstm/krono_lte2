@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT List to Approve/Verify</div>
    <div class="panel-body">
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
                        <th>No</th>
                        <th>Reference No</th>
                        <th>Date time</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                    <tr>
                        <td><input type="checkbox" id="checkbox-{{$no}}" value="{{$singleuser->id}}"></td>
                        <td>{{ ++$no }}</td>
                        <td>{{ $singleuser->refno }}<p>{{ $singleuser->name->name }}</p></td>
                        <td>{{ $singleuser->date }} @foreach($singleuser->detail as $details)<p>{{date('H:i', strtotime($details->start_time)) }} - {{ date('H:i', strtotime($details->end_time))}}</p>@endforeach</td>
                        <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                        <td>{{ $singleuser->status }} <p style="color: red">Due: {{$singleuser->date_expiry}}</p></td>
                        <td>
                            <form action="{{route('ot.queue')}}" method="POST" style="display:inline">
                                @csrf
                                <input type="text" class="hidden" id="inputid" name="inputid" value="{{$singleuser->id}}" required>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-user-secret"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="submitbtn" class="text-center" style="display: none">
            <form id="formquery" action="{{route('ot.queue')}}" method="POST" style="display:inline">
                @csrf
                <input type="text" class="hidden" id="queryid" name="queryid" value='' required>
                <input type="text" class="hidden" id="multi" name="multi" value="yes" required>
                <button type="submit" id="formsubmit" class="btn btn-primary"><i class="far fa-check-square"></i> APPROVE/VERIFY</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tOTList').DataTable({
        "responsive": "true",
        "order" : [[1, "asc"]],
    });
});
var show = 0;

function submitval(i){
    return function(){
        if ($('#checkbox-'+i).is(':checked')) {
            $("#queryid").val(function() {
                return this.value + $('#checkbox-'+i).val()+" ";
            });
            show++;
        }else{
            var str = ($('#queryid').val()).replace($('#checkbox-'+i).val()+" ",'');
            $('#queryid').val(str);
            show--;
        }
        if(show>0){
            $('#submitbtn').css("display","block");
        }
        else{
            $('#submitbtn').css("display","none");
        }
        if(show>1){
            $("#multi").val("yes");
        }else{
            $("#multi").val("x");
        }
    };
};

for(i=0; i<{{count($otlist)}}; i++) {
    $("#checkbox-"+i).change(submitval(i));
    if ($('#checkbox-'+i).is(':checked')) {
        show++;
        $('#submitbtn').css("display","block");
    }
    
    if(show>1){
        $("#multi").val("yes");
    }else{
        $("#multi").val("x");
    }
};

// $("#formsubmit").on("click", function(){
//     $("#queryid").val(function() {
//         return this.value + '"';
//     });
//     $("#formquery").submit();
// });

</script>
@stop