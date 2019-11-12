@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT Approval/Verification @if($mass ?? '')(Mass Action)@endif</div>
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
                    @foreach($otlist as $no=>$singleuser)
                    <input type="text" class="form-control hidden" id="inputid" name="inputid[]" value="{{$singleuser->id}}" required>
                    <thead>
                        <tr>
                            @if($mass ?? '')(Mass Action)<th>No</th>@endif
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
                            @if($mass ?? '')(Mass Action)<td>{{++$no}}</td>@endif
                            <td>{{ $singleuser->refno }}<p>{{ $singleuser->name->name }}</p></td>
                            <td>{{ $singleuser->date }}</td>
                            <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                            <td>{{ $singleuser->total_hour }}</td>
                            <td></td>
                            <td>{{ $singleuser->status }}</td>
                            <td>
                                <select name="inputaction[]" required>
                                    <option hidden disabled selected value="">Select Action</option>
                                    @if($singleuser=="Pending Verification")<option value="Verify">Verify</option>
                                    @elseif($singleuser=="Pending Approval")<option value="Approve">Approve</option>
                                    @endif
                                    <option value="Verify">Query</option>
                                    <option value="Verify">Reject</option>
                                </select>
                            </td>
                        </tr>
                        <tr>Justification: <textarea rows = "2" cols = "60" type="text"  id="inputremark" name="inputremark" value="" placeholder="Write justification" style="resize: none; display: inline" required></textarea></tr>
                    </tbody>
                </table>
            </div>
            @endforeach
            <div id="submitbtn" class="text-center"  onsubmit="return confirm('I understand and agree this to claim. If deemed false I can be taken to disciplinary action.')" style="display: none">
                <input type="text" class="hidden" id="queryid" name="queryid" value="" required>
                <input type="text" class="hidden" id="multi" name="multi" value="yes" required>
                <button type="submit" class="btn btn-primary"><i class="far fa-check-square"></i>SUBMIT</button>
            </div>
        </form>
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
            $("#submitid").val(function() {
                return this.value + $('#checkbox-'+i).val()+" ";
            });
            $("#deleteid").val(function() {
                return this.value + $('#checkbox-'+i).val()+" ";
            });
            show++;
        }else{
            var str = ($('#submitid').val()).replace($('#checkbox-'+i).val()+" ",'');
            $('#submitid').val(str);
            $('#deleteid').val(str);
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