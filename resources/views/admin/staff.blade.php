@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Search for Staff</div>
    <div class="panel-body">
        <!-- <h3>Search User</h3> -->
        <form action="{{ route('staff.search.admin') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus>
            </div>
            <button type="submit" class="btn btn-primary">SEARCH</button>
        </form>
    </div>
</div>
<div class="panel panel-default">    
    <div class="panel-heading panel-primary">List of Staff</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="tStaffList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $singleuser)
                    <tr>
                        <td>{{ $singleuser->staff_no }}</td>
                        <td>{{ $singleuser->name }}</td>
                        <td>{{ $singleuser->email }}</td>
                        @if($auth ?? '')
                        <td>@foreach ($singleuser->roles as $indexKey => $user)<p>{{$indexKey+1}}. {{ $user->title }}</p>@endforeach</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRole" data-role_id="{{$singleuser['id']}}" data-role_no="{{$singleuser['staff_no']}}" data-role_name="{{$singleuser['name']}}" data-role_user="@foreach ($singleuser->roles as $user){{ $user->id }} @endforeach">
                                <i class="fas fa-cog"></i>
                            </button>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>   
    </div>
</div>

<div id="editRole" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Role</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('staff.editrole.admin')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <p><b>Staff No:</b></p>
                    <p id="showno"><p>
                    <p><b>Staff Name:</b></p>
                    <p id="showname"><p>
                    <input type="text" class="form-control hidden" id="inputname" name="inputname" value="" required>
                    <input type="text" class="form-control hidden" id="inputno" name="inputno" value="" required>
                    <p><b>Set Roles:</b></p>
                    <div style="max-height: 210px; overflow-y: scroll">
                        @foreach($roles as $indexKey => $singlerole)
                        <div class="checkbox">
                            <label><input type="checkbox" id="checkbox_{{$indexKey+1}}" name="role[]" value="{{$singlerole->id}}">{{$singlerole->title}}</label>
                        </div>
                        @endforeach
                    </div>
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

@if($feedback ?? '')
<div id="feedback" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-{{$feedback_icon}}" style="color: {{$feedback_color}}; font-size: 32px;"></div>
                <p>{{$feedback_text}}<p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
@endif

@stop

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $('#tStaffList').DataTable({
        "responsive": "true",
        "order" : [[0, "desc"]],
        "columns": [
            null,
            null,
            null,
            null,
            { "width": "5%" }
        ]
    });
});

$('#editRole').on('show.bs.modal', function(e) {
    var role_id = $(e.relatedTarget).data('role_id');
    var role_name = $(e.relatedTarget).data('role_name')
    var role_no = $(e.relatedTarget).data('role_no')
    var role_user = $(e.relatedTarget).data(('role_user'));
    var role_users = role_user.split(" ");
    $('#showno').text(role_no);
    $('#showname').text(role_name);
    $('input[name=inputid]').val(role_id);
    $('input[name=inputname]').val(role_name);
    $('input[name=inputno]').val(role_no);
    for(i=0; i<role_users.length; i++){
        $("#checkbox_"+role_users[i]).prop('checked', true);
    }
});


@if($feedback ?? '')
    $('#feedback').modal('show');   
@endif
</script>
@stop