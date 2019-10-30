@extends('adminlte::page')

@section('title', 'Overtime List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">OT Application List {{$claimdate}} ({{$claimday}})</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-6">
                <p>Reference No: {{$claim->refno}}</p>
                <p>State Calendar: </p>
                <span style="color: red"><p>Due Date: {{$claim->date_expiry}}</p>
                <p>Unsubmitted claims will be deleted after the due date</p></span>
            </div>
            <div class="col-xs-6">
                <p>Status: {{$claim->status}}</p>
                <p>Verifier:</p>
                <p>Approver:</p>
            </div>
        </div>
        
        <div class="text-right" style="margin-bottom: 15px">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newOT">
                ADD TIME
            </button>
        </div>
        <div class="table-responsive">
            <table id="tOTList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Clock In/Out</th>
                        <th>OT time</th>
                        <th>Total Hour</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($otlist as $no=>$singleuser)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $singleuser->refno }}</td>
                        <td>{{ $singleuser->title }}</td>
                        <td>{{ $singleuser->total_hour }}h {{ $singleuser->total_minute }}m</td>
                        <td>
                        <input type="datetime-local">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRole">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRole">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="newOT" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Role</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('ot.addtime')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="{{$claim->id}}" required>
                    <div class="form-group">
                        <label for="inputname">Clock In/Out:</label>
                        <!-- <select name="company" id="company" required>
                        {{-- @if($companies ?? '')
                            @foreach($companies as $singlecompany)
                            <option value="{{$singlecompany->id}}">{{$singlecompany->company_descr}}</option>
                            @endforeach
                        @endif --}}
                        </select> -->
                    </div>
                    <!-- <div class="form-group"> -->
                        <label for="inputname">Start/End Time:</label>
                        <input type="time"  id="inputstart" name="inputstart" value="00:00" required>
                        <input type="time" id="inputend" name="inputend" value="23:59" required>
                    <!-- </div> -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">SAVE</button>
                    </div>
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
// $(document).ready(function() {
//     $('#tOTList').DataTable({
//         "responsive": "true",
//         "order" : [[2, "asc"]],
//     });
// });

</script>
@stop