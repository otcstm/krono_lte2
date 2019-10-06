@extends('adminlte::page')

@section('title', 'Search Staff')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Search for Staff</div>
    <div class="panel-body">
        <!-- <h3>Search User</h3> -->
        <form action="{{ route('staff.search') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" id="inputstaff" name="inputstaff" placeholder="{{ __('adminlte::adminlte.input_staff') }}" value="{{ old('inputstaff') }}" autofocus>
            </div>
            @if($auth ?? '')
            <input type="text" class="form-control hidden" id="auth" name="auth" value="auth">
            @elseif($mgmt ?? '')
            <input type="text" class="form-control hidden" id="mgmt" name="mgmt" value="mgmt">
            @endif
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
                        @if($auth ?? '')
                        <th>Roles</th>
                        @else
                        <th>Company</th>
                        <th>State</th>
                        @endif
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
                        @else
                        <td>@if($singleuser->company_id ?? ''){{ $singleuser->companyid->company_descr }}@endif</td>
                        <td>@if($singleuser->state_id ?? ''){{ $singleuser->stateid->state_descr }}@endif</td>
                            @if($mgmt ?? '')
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editMgmt" data-role_id="{{$singleuser['id']}}" data-role_no="{{$singleuser['staff_no']}}" data-role_name="{{$singleuser['name']}}"data-role_company="{{$singleuser['company_id']}}" data-role_state="{{$singleuser['state_id']}}">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </td>
                            @else
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info" data-role_id="{{$singleuser['id']}}" data-role_no="{{$singleuser['staff_no']}}" data-role_name="{{$singleuser['name']}}" data-role_email="{{$singleuser['email']}}" data-role_company="@if($singleuser->company_id ?? ''){{ $singleuser->companyid->company_descr }}@endif" data-role_state="@if($singleuser->state_id ?? ''){{ $singleuser->stateid->state_descr }}@endif">
                                    <i class="fas fa-info"></i>
                                </button>
                            </td>
                            @endif
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
                <form action="{{route('staff.edit.auth')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <p><b>Staff No:</b></p>
                    <p class="showno"></p>
                    <p><b>Staff Name:</b></p>
                    <p class="showname"></p>
                    <input type="text" class="form-control hidden" id="inputname" name="inputname" value="" required>
                    <input type="text" class="form-control hidden" id="inputno" name="inputno" value="" required>
                    <p><b>Set Roles:</b></p>
                    <div style="max-height: 210px; overflow-y: scroll">
                    @if($roles ?? '')
                        @foreach($roles as $indexKey => $singlerole)
                        <div class="checkbox">
                            <label><input type="checkbox" id="checkbox_{{$indexKey+1}}" name="role[]" value="{{$singlerole->id}}">{{$singlerole->title}}</label>
                        </div>
                        @endforeach
                    @endif
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

<div id="editMgmt" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit User Info</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('staff.edit.mgmt')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <p><b>Staff No:</b></p>
                    <p class="showno"><p>
                    <p><b>Staff Name:</b></p>
                    <p class="showname"><p>
                    <input type="text" class="form-control hidden" id="inputname" name="inputname" value="" required>
                    <input type="text" class="form-control hidden" id="inputno" name="inputno" value="" required>
                    <p><b>Company:</b></p>
                    <select name="company" id="company" required>
                    @if($companies ?? '')
                        @foreach($companies as $singlecompany)
                        <option value="{{$singlecompany->id}}">{{$singlecompany->company_descr}}</option>
                        @endforeach
                    @endif
                    </select>
                    <br><br><p><b>State:</b></p>
                    <select name="state" id="state" required>
                    @if($states ?? '')
                        @foreach($states as $singlestate)
                        <option value="{{$singlestate->id}}">{{$singlestate->state_descr}}</option>
                        @endforeach
                    @endif
                    </select>
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

<div id="info" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">User Details</h4>
            </div>
            <div class="modal-body">
                <p><b>Staff No:</b></p>
                <p class="showno"><p><br>
                <p><b>Staff Personal No:</b></p>
                <p class="showid"><p><br>
                <p><b>Staff Name:</b></p>
                <p class="showname"><p><br>
                <p><b>Staff Email:</b></p>
                <p class="showemail"><p><br>
                <p><b>Company:</b></p>
                <p class="showncompany"><p><br>
                <p><b>State:</b></p>
                <p class="showstate"><p>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if(session()->has('feedback'))
<div id="feedback" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-{{session()->get('feedback_icon')}}" style="color: {{session()->get('feedback_color')}}; font-size: 32px;"></div>
                <p>{{session()->get('feedback_text')}}<p>
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
            null
            @if($auth ?? '')
            @else
            ,null
            @endif
            ,{ "width": "5%" }
        ]
    });
});

function populate(e){
    var role_id = $(e.relatedTarget).data('role_id');
    var role_name = $(e.relatedTarget).data('role_name');
    var role_no = $(e.relatedTarget).data('role_no');
    var role_email = $(e.relatedTarget).data('role_email');
    var role_company = $(e.relatedTarget).data('role_company');
    var role_state = $(e.relatedTarget).data('role_state');
    @if($auth ?? '')
    var role_user = $(e.relatedTarget).data(('role_user'));
    var role_users = role_user.split(" ");
    @endif
    $('.showno').text(role_no);
    $('.showname').text(role_name);
    $('.showid').text(role_id);
    $('.showemail').text(role_email);
    $('.showncompany').text(role_company);
    $('.showstate').text(role_state);
    $('input[name=inputid]').val(role_id);
    $('input[name=inputname]').val(role_name);
    $('input[name=inputno]').val(role_no);
    $("#company").val(role_company);
    $("#state").val(role_state);
    @if($auth ?? '')
    for(i=0; i<role_users.length; i++){
        $("#checkbox_"+role_users[i]).prop('checked', true);
    }

    @endif
}

$('#editRole').on('show.bs.modal', function(e) {
    populate(e);
});

$('#editMgmt').on('show.bs.modal', function(e) {
    populate(e);
});

$('#info').on('show.bs.modal', function(e) {
    populate(e);
});

@if(session()->has('feedback'))
    $('#feedback').modal('show');   
@endif
</script>
@stop