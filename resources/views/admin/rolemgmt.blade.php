@extends('adminlte::page')

@section('title', 'Roles List')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading panel-primary">Roles</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="tRoleList" class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Created by</th>
                        <th>Permissions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $singleuser)
                    <tr>
                        <td>{{ $singleuser->id }}</td>
                        <td>{{ $singleuser->title }}</td>
                        <td>{{ $singleuser->createdby->name }}</td>
                        <td>@foreach ($singleuser->permissions as $indexKey => $user)<p>{{$indexKey+1}}. {{ $user->title }}</p>@endforeach</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editRole" data-role_id="{{$singleuser['id']}}" data-role_name="{{$singleuser['title']}}" data-role_permission="@foreach ($singleuser->permissions as $user){{ $user->id }} @endforeach">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRole" data-role_id="{{$singleuser['id']}}" data-role_name="{{$singleuser['title']}}" data-role_permission="@foreach ($singleuser->permissions as $user){{ $user->id }} @endforeach">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newRole">
                CREATE NEW ROLE
            </button>
        </div>
    </div>
</div>

<div id="newRole" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Role</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('role.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="inputname">Role Name:</label>
                        <input type="text" class="form-control" id="inputname" name="inputname" placeholder="{{ __('adminlte::adminlte.input_role_name') }}" value="{{ old('inputname') }}" required autofocus>
                    </div>
                    <p><b>Set Permissions:</b></p>
                    <div style="max-height: 210px; overflow-y: scroll">
                    @if($permissions ?? '')
                        @foreach($permissions as $singlerole)
                        <div class="checkbox">
                            <label><input type="checkbox" id="checkbox_{{$singlerole->id}}" name="permission[]" value="{{$singlerole->id}}">{{$singlerole->title}}</label>
                        </div>
                        @endforeach
                    @endif
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">CREATE</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
            </div>
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
                <form action="{{ route('role.edit') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <div class="form-group">
                        <label for="inputname">Role Name:</label>
                        <input type="text" class="form-control" id="inputname" name="inputname" value="" required autofocus>
                    </div>
                    <p><b>Set Permissions:</b></p>
                    <div style="max-height: 210px; overflow-y: scroll">
                    @if($permissions ?? '')
                        @foreach($permissions as $singlerole)
                        <div class="checkbox">
                            <label><input type="checkbox" class="checkbox_{{$singlerole->id}}" name="permission[]" value="{{$singlerole->id}}">{{$singlerole->title}}</label>
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

<div id="deleteRole" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Role</h4>
            </div>
            <div class="modal-body text-center">
                <div class="glyphicon glyphicon-warning-sign" style="color: #F0AD4E; font-size: 32px;"></div>
                <p>Are you sure you want to delete role <span id="showname"></span>?<p>
                <form action="{{ route('role.delete') }}" method="POST">
                    @csrf
                    <input type="text" class="form-control hidden" id="inputid" name="inputid" value="" required>
                    <input type="text" class="form-control hidden" id="inputname" name="inputname" value="" required>
                    <button type="submit" class="btn btn-primary">DELETE</button>
                </form>
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
    $('#tRoleList').DataTable({
        "responsive": "true",
        "order" : [[2, "asc"]],
        "columns": [
            null,
            null,
            null,
            null,
            { "width": "10%" }
        ]
    });
});

function populate(e){
    var role_id = $(e.relatedTarget).data('role_id');
    var role_name = $(e.relatedTarget).data('role_name')
    var role_permission = $(e.relatedTarget).data(('role_permission'));
    var role_permissions = role_permission.split(" ");
    $('input[name=inputid]').val(role_id);
    $('input[name=inputname]').val(role_name);
    $('#showname').text(role_name);
    for(i=0; i<role_permissions.length; i++){
        $(".checkbox_"+role_permissions[i]).prop('checked', true);
    }
}

$('#editRole').on('show.bs.modal', function(e) {
    populate(e);
});

$('#deleteRole').on('show.bs.modal', function(e) {
    populate(e);
});

@if(session()->has('feedback'))
    $('#feedback').modal('show');   
@endif

</script>
@stop